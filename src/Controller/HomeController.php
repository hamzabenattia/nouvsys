<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
        
        ]);
    }


    #[Route('/qui-sommes-nous', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('about/index.html.twig');
    }


    // #[Route('/contact', name: 'app_contact' , methods: ['POST'])]
    // public function contact(Request $request): Response
    // {
    //     $form = $this->createForm(ContactFormType::class);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         dd($form->getData());
    //     }

    //     return $this->render('about/index.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }
}
