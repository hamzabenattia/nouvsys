<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Repository\CandidateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mes-candidats')]
class UserCandidateController extends AbstractController
{
    #[Route('/', name: 'app_user_candidate_index', methods: ['GET'])]
    public function index(CandidateRepository $candidateRepository , #[CurrentUser] User $user): Response
    {
        return $this->render('pages/user_candidate/index.html.twig', [
            'candidates' => $candidateRepository->findAllByUser($user->getEmail()),
        ]);
    }


    #[Route('/{id}', name: 'app_user_candidate_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Candidate $candidate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidate->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($candidate);
            $entityManager->flush();
            $this->addFlash('success', 'Candidat supprimé avec succès');
        }


        return $this->redirectToRoute('app_user_candidate_index', [], Response::HTTP_SEE_OTHER);
    }
}
