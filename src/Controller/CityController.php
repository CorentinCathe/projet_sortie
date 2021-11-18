<?php

namespace App\Controller;

use App\Data\SearchDataCity;
use App\Entity\City;
use App\Form\CityType;
use App\Form\SearchCityForm;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/city")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/", name="city_index", methods={"GET","POST"})
     */
    public function index(CityRepository $cityRepository, Request $request): Response
    {
        $data = new SearchDataCity();
        $dataAdd = new City();
        $form = $this->createForm(SearchCityForm::class, $data);
        $formAdd = $this->createForm(CityType::class, $dataAdd);
        $form->handleRequest($request);
        $user = $this->getUser();
        $formAdd->handleRequest($request);
        if ($formAdd->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($dataAdd);
            $em->flush();
        }
        return $this->render('city/index.html.twig', [
            'citys' => $cityRepository->findSearch($data, $user),
            'user' => $user,
            'form' => $form->createView(),
            'form_add' => $formAdd->createView()
        ]);
    }

    /**
     * @Route("/new", name="city_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('city_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('city/new.html.twig', [
            'city' => $city,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="city_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, City $city): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('city_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('city/edit.html.twig', [
            'city' => $city,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="city_delete")
     */
    public function delete(City $city): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($city);
        $entityManager->flush();
        return $this->redirectToRoute('city_index');
    }
}
