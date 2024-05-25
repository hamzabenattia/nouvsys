<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Offres;
use App\Entity\SpontaneousCandidate;
use App\Entity\User;
use App\Form\CandidateFormType;
use App\Form\SpontaneousCandidateType;
use App\Repository\CandidateRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CandidateController extends AbstractController
{

    public function __construct(private EmailSender $emailSender)
    {
    }


    #[Route('/joindre-nos', name: 'app_candidate')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $candidate = new SpontaneousCandidate();
        $form = $this->createForm(SpontaneousCandidateType::class, $candidate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($candidate);
            $em->flush();
            $this->addFlash('success', 'Votre candidature a bien été enregistrée');

            $this->emailSender->sendEmail(
                new Address('noreply@nouvsys.fr', 'noreply'),
                $candidate->getEmail(),
                'Votre candidature a bien été enregistrée',
                'emails/candidat_spontaneous.html.twig',
                [
                    'candidate' => $candidate,
                ]
            );


            return $this->redirectToRoute('app_candidate');
        }

        return $this->render('pages/candidate/index.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/candidature/{id}', name: 'app_offre_postule', methods: ['GET', 'POST'])]
    #[IsGranted('OFFRE_VIEW', 'offres',  'Erreur 404', 404 )]
    public function offre(Request $request, EntityManagerInterface $em, Offres $offres, CandidateRepository $cr,  #[CurrentUser] User $user): Response
    {

        $candidate = new Candidate();
        $form = $this->createForm(CandidateFormType::class, $candidate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $candidate->setType(Candidate::TYPE_OFFER);
            $candidate->setOffre($offres);
            $candidate->setUser($user);
            $em->persist($candidate);
            $em->flush();

            $this->addFlash('success', 'Votre candidature a bien été enregistrée pour l\'offre ' . $offres->getTitle());

            $this->emailSender->sendEmail(
                new Address('noreply@nouvsys.fr', 'noreply'),
                $candidate->getUser()->getEmail(),
                'Votre candidature a bien été enregistrée',
                'emails/candidat_offre.html.twig',
                [
                    'candidate' => $candidate,
                    'offres' => $offres,
                ]
            );

            return $this->redirectToRoute('app_offres');
        }

        $userAlreadyApplied = $cr->findOneBy([
            'offre' => $offres,
            'user' => $user,
        ]);
        return $this->render('pages/candidate/offre.html.twig', [
            'form' => $form,
            'user' => $user,
            'offre' => $offres,
            'disable' => $userAlreadyApplied ? true : false

        ]);
    }
}
