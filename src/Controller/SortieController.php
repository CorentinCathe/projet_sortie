<?php

namespace App\Controller;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Sortie;
use App\Form\PlaceType;
use App\Form\SortieType;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use App\Repository\SortieRepository;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use phpDocumentor\Reflection\Types\This;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }
  
     /**
     * @Route("/sortieAdd/{id}", name="sortieAdd")
     */
  public function sortieAdd(Request $request, SortieRepository $sortieRepo, User $user, PlaceRepository $placeRepo, CityRepository $cityRepo): Response {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $place = new Place();
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);
        /*$city = new City();
        $cityForm = $this->createForm(City::class, $city);
        $cityForm->handleRequest($request);*/
        $sortieForm->handleRequest($request);
        if($placeForm->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();
        }

        if($sortieForm-> isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $sortie->setOrganisator($user);
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('main');
        }
        return $this->render('sortie/addSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'placeForm' => $placeForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie): Response
    {
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
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"POST"})
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
    }
}
