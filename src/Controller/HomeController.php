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

    #[Route('/nous-competences', name: 'app_skills')]
    public function skills(): Response
    {
        return $this->render('skills/index.html.twig');
    }

}
