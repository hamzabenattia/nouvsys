<?php

namespace App\Controller;

use App\Entity\Offres;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nos-offres')]
class OffresController extends AbstractController
{
    #[Route('/', name: 'app_offres', methods: ['GET'])]
    public function index(): Response
    {
       
        return $this->render('pages/offres/index.html.twig', [
        ]);
    }

    #[Route('/{id}', name: 'app_offres_show', methods: ['GET'])]
    public function show(Offres $offre): Response
    {
        return $this->render('pages/offres/show.html.twig', [
            'offre' => $offre,
        ]);
    }

}
