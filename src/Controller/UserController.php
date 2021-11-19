<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/admin/user/add/{id}", name="user_add")
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
            $user->setActiv(false);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/userAdd.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/admin/user/index/", name="user_index")
     */
    public function userIndex(UserRepository $userRepo): Response
    {
        $userList = $userRepo->findAll();
        return $this->render('user/adminUsers.html.twig', [
            'users' => $userList
        ]);
    }

    /**
     * @Route("/admin/user/profil/{id}", name="admin_profil")
     */
    public function profilAsAdmin(User $user): Response
    {
        return $this->render('profil/adminDisplayProfil.html.twig', [
            'user' => $user
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
