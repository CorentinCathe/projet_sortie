<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Sortie;
use App\Form\PlaceType;
use App\Form\SortieInfoType;
use App\Form\SortieType;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use App\Repository\SortieRepository;
use App\Repository\StatusRepository;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        $user = $this->getUser();
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/sortieAdd/{id}", name="sortieAdd")
     */
    public function sortieAdd(Request $request, User $user): Response
    {
        if ($this->getUser()) {
            $sortie = new Sortie();
            $sortieForm = $this->createForm(SortieType::class, $sortie);
            $place = new Place();
            $placeForm = $this->createForm(PlaceType::class, $place);
            $placeForm->handleRequest($request);
            /*$city = new City();
        $cityForm = $this->createForm(City::class, $city);
        $cityForm->handleRequest($request);*/
            $sortieForm->handleRequest($request);
            if ($placeForm->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($place);
                $em->flush();
            }

            if ($sortieForm->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $sortie->setOrganisator($user);
                $em->persist($sortie);
                $em->flush();
                return $this->redirectToRoute('sortie_index');
            }
            return $this->render('sortie/addSortie.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'placeForm' => $placeForm->createView(),
            ]);
        } else {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/show/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        $user = $this->getUser();
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'user' => $user,
            'users' => $sortie->getUser()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Sortie $sortie, Request $request): Response
    {
        if ($this->getUser() == $sortie->getOrganisator()) {
            $form = $this->createForm(SortieType::class, $sortie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('sortie/edit.html.twig', [
                'sortie' => $sortie,
                'form' => $form,
            ]);
        } else {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/delete/{id}", name="sortie_delete", methods={"POST"})
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->getUser() == $sortie->getOrganisator()) {
            if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($sortie);
                $entityManager->flush();
            }

            return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
        } else {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/cancel/{id}", name="sortie_cancel")
     */
    public function cancel(Sortie $sortie, Request $request, StatusRepository $statusRepo): Response
    {
        if ($this->getUser() == $sortie->getOrganisator()) {
            $sortieForm = $this->createForm(SortieInfoType::class, $sortie);
            $sortieForm->handleRequest($request);
            if ($sortieForm->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $sortie->setStatus($statusRepo->find(6));
                $em->persist($sortie);
                $em->flush();
                return $this->redirectToRoute('sortie_index');
            }
            return $this->renderForm('sortie/cancel.html.twig', [
                'sortie' => $sortie,
                'sortieForm' => $sortieForm,
            ]);
        } else {
            throw $this->createAccessDeniedException();
        }
    }


    /**
     * @Route("/sign-in/{id}", name="sign_in")
     */
    public function signIn(Sortie $sortie): Response
    {
        if ($this->getUser() != $sortie->getOrganisator()) {
            $user = $this->getUser();
            $sortie->addUser($user);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('main');
        } else {
            throw $this->createAccessDeniedException();
        }
    }
}
