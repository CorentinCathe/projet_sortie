<?php


namespace App\Controller;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use phpDocumentor\Reflection\Types\This;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    /**
     * @Route("/sortieAdd/{id}", name="sortieAdd")
     */
    public function userAdd(Request $request, SortieRepository $sortieRepo, User $user): Response {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);
        if($sortieForm-> isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $sortie->setOrganisator($user);
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('main');
        }
        return $this->render('sortie/addSortie.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

}