<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('pages/home/index.html.twig', [
        
        ]);
    }


    #[Route('/qui-sommes-nous', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('pages/about/index.html.twig');
    }

    #[Route('/nous-competences', name: 'app_skills')]
    public function skills(): Response
    {
        return $this->render('pages/skills/index.html.twig');
    }

}
