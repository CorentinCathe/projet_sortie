<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
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

    /**
     * @Route("/admin/user/index/", name="user_index")
     */
    public function userIndex(Request $request, UserRepository $userRepo): Response
    {
        $userList = $userRepo->findAll();
        return $this->render('user/adminUsers.html.twig', [
            'users' => $userList
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="user_delete")
     */
    public function userDelete(User $user): Response
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/admin/user/desactivate/{id}", name="user_desactivate")
     */
    public function userDesactivate(User $user): Response
    {

        $user->setActiv(!$user->getActiv());
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('user_index');
    }

}