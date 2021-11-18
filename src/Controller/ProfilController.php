<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function getProfil(): Response
    {
        return $this->render('profil/displayProfil.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/profil/participant/{id}", name="profil_participant")
     */
    public function getParticipant(User $participant): Response
    {
        return $this->render('profil/displayParticipatorProfil.html.twig', [
            'user' => $participant
        ]);
    }


    /**
     * @Route("/modify_profil/{id}", name="modify")
     */
    public function modify(User $user, Request $req): Response
    {
        if ($this->getUser()->getId()  == $user->getId()) {
            $formProfil = $this->createForm(ProfilType::class, $user);

            $formProfil->handleRequest($req);
            if ($formProfil->isSubmitted()) {

                $uploadedFile = $formProfil['profilPictureFile']->getData();
                if ($uploadedFile) {
                    $filesystem = new Filesystem;
                    $filesystem->remove('public\uploads\article_image\profil.*');
                    $destination = $this->getParameter('kernel.project_dir') . '/public/assets/uploads/article_image';
                    $newFilename = 'profil.' . $uploadedFile->guessExtension();
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                    $user->setProfilPicture($newFilename);
                }


                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash('success', 'Modifications Réussi');
                return $this->redirectToRoute('profil');
            }

            return $this->render('profil/modifyProfil.html.twig', [
                'formProfil' => $formProfil->createView()
            ]);
        } else {
            throw $this->createAccessDeniedException();
        }
    }
}
