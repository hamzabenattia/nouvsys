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
    
    private $emailSender;

    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }



    #[Route('/contact', name: 'app_contact' , methods: ['POST'])]
    public function contact(Request $request): Response
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
            'houcine.z@sonaso.fr',  
            'Nouveau message de contact Nouvsys',
            'emails/contact.html.twig',
            $context); 

            $this->addFlash('success', 'Votre message a été envoyé avec succès');
            return $this->redirectToRoute('app_home');
                    
        }

        return $this->render('about/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}