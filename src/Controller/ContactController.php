<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\EmailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    
    public function __construct(private EmailSender $emailSender)
    {
    }


    #[Route('/contact', name: 'app_contact' , methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $context = [
                'nom' => $data['nom'],
                'numero' => $data['numero'],
                'senderEmail' => $data['email'],
                'message' => $data['message'],
            ];

            $this->emailSender->sendEmail($data['email'],
            'hamzabenattiayt2@gmail.com',  
            'Nouveau message de contact Nouvsys',
            'emails/contact.html.twig',
            $context); 

            $this->addFlash('success', 'Votre message a été envoyé avec succès');
            return $this->redirectToRoute('app_home');          
        }

        return $this->render('pages/contact/index.html.twig', [
            'contactForm' => $form
        ]    );
    }

}
