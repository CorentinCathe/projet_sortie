<?php

namespace App\Controller;

use App\Data\SearchDataSite;
use App\Entity\Site;
use App\Form\SearchSiteForm;
use App\Form\SearchSortieForm;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/site")
 */
class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site_index", methods={"GET","POST"})
     */
    public function index(SiteRepository $siteRepository,Request $request): Response
    {
        $data = new SearchDataSite();
        $dataAdd = new Site();
        $form = $this->createForm(SearchSiteForm::class, $data);
        $formAdd = $this->createForm(SiteType::class, $dataAdd);
        $form->handleRequest($request);
        $formAdd->handleRequest($request);
        if ($formAdd->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($dataAdd);
            $em->flush();
        }
        $user = $this->getUser();
        return $this->render('site/index.html.twig', [
            'sites' => $siteRepository->findSearch($data, $user),
            'user' => $user,
            'form' => $form->createView(),
            'form_add' => $formAdd->createView(),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="site_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Site $site): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/edit.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="site_delete")
     */
    public function delete(Site $site): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($site);
        $entityManager->flush();
        return $this->redirectToRoute('site_index');
    }
}
