<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\SiteRepository;
use phpDocumentor\Reflection\Types\This;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/admin/user/add/{id}", name="userAdd")
     */
    public function userAdd(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $user->setRoles(['User' => 'ROLE_USER']);
            $user->setPassword($encoder->hashPassword($user, $user->getPassword()));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('sortie_index');
        }
        return $this->render('user/userAdd.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

}