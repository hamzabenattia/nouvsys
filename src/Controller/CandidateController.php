<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateFormType;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class CandidateController extends AbstractController
{
   private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }



    #[Route('/joindre-nos', name: 'app_candidate')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $candidate = new Candidate();
        $form = $this->createForm(CandidateFormType::class, $candidate);
        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($candidate);
            $em->flush();
            $this->addFlash('success', 'Votre candidature a bien été enregistrée');


           $email =(new Email())
            ->from('noreplay@nouvsys.fr')
            ->to($candidate->getEmail())
            ->subject('Votre candidature a bien été enregistrée')
            ->html('
            <div class="text-center">
            <p>Bonjour '.$candidate->getFirstname().'</p>
            <p>Votre candidature a bien été enregistrée</p>
            <p>Nous vous remercions pour votre confiance</p>
            <p>L\'équipe de Nouvsys</p>
            </div>
           
            ');
            $this->mailer->send($email);


            return $this->redirectToRoute('app_candidate');
        }
          

        return $this->render('pages/candidate/index.html.twig', [
            'form' => $form,
        ]);
    }
}
