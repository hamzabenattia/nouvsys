<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request,Security $security, #[CurrentUser] User $user , EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->redirectToRoute('app_profile');

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès !');
            return $this->redirectToRoute('app_profile');          



        }


        $ChangePasswordform = $this->createForm(ChangePasswordType::class, $user);
        $ChangePasswordform->handleRequest($request);

        if ($ChangePasswordform->isSubmitted() && $ChangePasswordform->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès ! sil vous plait connectez-vous à nouveau.');
            return $security->logout(validateCsrfToken: false) ?? $this->redirectToRoute('app_login');
        }


        return $this->render('pages/profile/index.html.twig', [
            'profileForm' => $form,
            'changePasswordForm' => $ChangePasswordform
        ]);
    }
}
