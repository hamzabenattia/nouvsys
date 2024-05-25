<?php

namespace App\Controller;

use App\Entity\Offres;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('OFFRE_VIEW', 'offre',  "L'offre n'existe pas ", 404 )]
    public function show(Offres $offre): Response
    {

        return $this->render('pages/offres/show.html.twig', [
            'offre' => $offre,
        ]);
    }

}
