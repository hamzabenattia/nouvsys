<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Offres;
use App\Entity\User;
use App\Form\CandidateFormType;
use App\Repository\CandidateRepository;
use App\Repository\OffresRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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
            $candidate->setType(Candidate::TYPE_SPONTANEOUS);
            $em->persist($candidate);
            $em->flush();
            $this->addFlash('success', 'Votre candidature a bien été enregistrée');


            $email = (new Email())
                ->from('noreplay@nouvsys.fr')
                ->to($candidate->getEmail())
                ->subject('Votre candidature a bien été enregistrée')
                ->html('
            <div class="text-center">
            <p>Bonjour ' . $candidate->getFirstname() . '</p>
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


    #[Route('/candidature/{id}', name: 'app_offre_postule', methods: ['GET','POST'])]
    public function offre(Request $request, EntityManagerInterface $em, Offres $offres , CandidateRepository $cr,  #[CurrentUser] User $user ) : Response
    {

        $candidate = new Candidate();
        $form = $this->createForm(CandidateFormType::class, $candidate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $candidate->setType(Candidate::TYPE_OFFER);
            $candidate->setOffre($offres);
            $candidate->setFirstName($user->getFirstName());
            $candidate->setLastName($user->getLastName());
            $candidate->setEmail($user->getEmail());
            $candidate->setPhoneNumber($user->getPhoneNumber());
            $em->persist($candidate);
            $em->flush();

            $this->addFlash('success', 'Votre candidature a bien été enregistrée pour l\'offre ' . $offres->getTitle());
            
            $email = (new Email())
                ->from('noreplay@nouvsys.fr')
                ->to($candidate->getEmail())
                ->subject('Votre candidature a bien été enregistrée')
                ->html(' 
            <div class="text-center">
            <p>Bonjour ' . $candidate->getFirstname() . '</p>
            <p>Votre candidature a bien été enregistrée pour l\'offre ' . $offres->getTitle() . '</p>            
            <p>Vous pouvvez gérer votre candidature depuis votre espace candidat</p> <a href="">Espace candidat</a>
            <p>Nous vous remercions pour votre confiance</p>
            <p>L\'équipe de Nouvsys</p>
            </div>')
            ;
            $this->mailer->send($email);
            return $this->redirectToRoute('app_offres');

    }
    
    $userAlreadyApplied = $cr->findOneBy([
        'offre' => $offres,
        'email' => $user->getEmail()
    ]);
        return $this->render('pages/candidate/offre.html.twig', [
            'form' => $form,
            'user' => $user, 
            'offre' => $offres,
            'disable' => $userAlreadyApplied ? true : false


        ]);
    }


}
