<?php

namespace App\Controller;

use App\Data\SearchDataSortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SearchSortieForm;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(SortieRepository $sortieRepository, Request $request): Response
    {
        if ($this->getUser()->getActiv()) {
            $data = new SearchDataSortie();
            $form = $this->createForm(SearchSortieForm::class, $data);
            $form->handleRequest($request);
            $user = $this->getUser();
            return $this->render('sortie/index.html.twig', [
                'sorties' =>  $sortieRepository->findSearch($data, $user),
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_logout');
        }
    }
}
