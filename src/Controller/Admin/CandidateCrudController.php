<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class CandidateCrudController extends AbstractCrudController
{
    public function __construct(private EmailSender $emailSender)
    {
    }



    public static function getEntityFqcn(): string
    {
        return Candidate::class;
    }

    public function configureActions(Actions $actions): Actions
    {

        $viewCV = Action::new('Télecharger le Cv', 'Télecharger le Cv', 'fa fa-download')
            ->linkToUrl(fn ($entity) => '/files/cv/' . $entity->getCv())
            ->displayIf(fn ($entity) => $entity->getCv() !== null);

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, $viewCV)
            ->add(Crud::PAGE_DETAIL, $viewCV)
            ->addBatchAction(Action::new('reject', 'Refuser')
        ->linkToCrudAction('rejectCandidate')
        ->addCssClass('btn btn-danger')
        ->setIcon('fa fa-ban'))
        ->addBatchAction(Action::new('approve', 'Accepter')
        ->linkToCrudAction('approveCandidate')
        ->addCssClass('btn btn-primary')
        ->setIcon('fa fa-check'));
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setDisabled()->hideOnForm(),
            TextField::new('user.firstName')->setLabel('Prénom')->setDisabled(),
            TextField::new('user.lastName')->setLabel('Nom')->setDisabled(),
            EmailField::new('user.email')->setLabel('Email')->setDisabled(),
            TelephoneField::new('user.phoneNumber')->setLabel('Téléphone')->setDisabled(),
            TextEditorField::new('message')->setLabel('Message')->hideOnForm(),
            AssociationField::new('offre')->autocomplete()->setDisabled(),
            ChoiceField::new('status')->setChoices([
                Candidate::STATUS_PENDING => Candidate::STATUS_PENDING,
                Candidate::STATUS_ACCEPTED => Candidate::STATUS_ACCEPTED,
                Candidate::STATUS_REFUSED => Candidate::STATUS_REFUSED,
            ])->renderAsBadges(
                [
                    Candidate::STATUS_PENDING => 'warning',
                    Candidate::STATUS_ACCEPTED => 'success',
                    Candidate::STATUS_REFUSED => 'danger',
                ]

            )->setLabel('Statut'),
            DateTimeField::new('createdAt')->setLabel('Date de postulation')->hideOnForm(),
            DateTimeField::new('updatedAt')->setLabel('Date de modification')->hideOnForm(),
            
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('createdAt', 'Date de postulation'))
            ->add(EntityFilter::new('offre', 'Offre'))
            ->add(EntityFilter::new('user','Candidate'))
            ->add(ChoiceFilter::new('status', 'Statut')->setChoices([
                Candidate::STATUS_PENDING => Candidate::STATUS_PENDING,
                Candidate::STATUS_ACCEPTED => Candidate::STATUS_ACCEPTED,
                Candidate::STATUS_REFUSED => Candidate::STATUS_REFUSED,
            ]));
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityManager->persist($entityInstance);
        $entityInstance->setupdatedAt(new \DateTimeImmutable());
        if ($entityInstance->getStatus() === Candidate::STATUS_ACCEPTED) {
            $this->emailSender->sendEmail(
            'noreply@nouvsys.fr',
            $entityInstance->getUser()->getEmail(),  
            'Votre candidature au poste de '.$entityInstance->getOffre()->getTitle(). ' Deuxième phase de sélection',
            'emails/candidat_accept.html.twig',
            [
                'candidate' => $entityInstance,
                'offres' => $entityInstance->getOffre(),
            ],
            );
        }else if($entityInstance->getStatus() === Candidate::STATUS_REFUSED){
            $this->emailSender->sendEmail(
                'noreply@nouvsys.fr',
                $entityInstance->getUser()->getEmail(),  
                'Suite à votre candidature au poste de '. $entityInstance->getOffre()->getTitle().' chez Nouvsys',
                'emails/candidat_refuse.html.twig',
                [
                    'candidate' => $entityInstance,
                    'offres' => $entityInstance->getOffre(),
                ],
                ); 
        }
        $entityManager->flush();
    }
    

    public function approveCandidate(BatchActionDto $batchActionDto, CandidateRepository $candidateRepository , EntityManagerInterface $entityManager)
    {
        foreach ($batchActionDto->getEntityIds() as $id) {
            $candidate = $candidateRepository->find($id);
            $candidate->setStatus(status: Candidate::STATUS_ACCEPTED);
    
            $this->emailSender->sendEmail(
                'noreply@nouvsys.fr',
                $candidate->getUser()->getEmail(),  
                'Votre candidature au poste de '.$candidate->getOffre()->getTitle(). ' Deuxième phase de sélection',
                'emails/candidat_accept.html.twig',
                [
                    'candidate' => $candidate,
                    'offres' => $candidate->getOffre(),
                ],
                );
        }
    
        $entityManager->flush();
    
        return $this->redirect($batchActionDto->getReferrerUrl());
    }




    public function rejectCandidate(BatchActionDto $batchActionDto, CandidateRepository $candidateRepository , EntityManagerInterface $entityManager)
    {
        foreach ($batchActionDto->getEntityIds() as $id) {
            $candidate = $candidateRepository->find($id);
            $candidate->setStatus(status: Candidate::STATUS_REFUSED);
    
            $this->emailSender->sendEmail(
                'noreply@nouvsys.fr',
                $candidate->getUser()->getEmail(),  
                'Suite à votre candidature au poste de '. $candidate->getOffre()->getTitle().' chez Nouvsys',
                'emails/candidat_refuse.html.twig',
                [
                    'candidate' => $candidate,
                    'offres' => $candidate->getOffre(),
                ],
                ); 
        }
    
        $entityManager->flush();
    
        return $this->redirect($batchActionDto->getReferrerUrl());
    }
}



