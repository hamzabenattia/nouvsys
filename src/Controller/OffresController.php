<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Form\OffresType;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nos-offres')]
class OffresController extends AbstractController
{
    #[Route('/', name: 'app_offres', methods: ['GET'])]
    public function index(OffresRepository $offresRepository): Response
    {
        return $this->render('pages/offres/index.html.twig', [
            'offres' => $offresRepository->findAll(),
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
