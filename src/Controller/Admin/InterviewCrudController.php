<?php

namespace App\Controller\Admin;

use App\Entity\Interview;
use App\Service\EmailSender;
use App\Service\GoogleMeetService;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class InterviewCrudController extends AbstractCrudController
{

    public function __construct(private EmailSender $emailSender, private GoogleMeetService $googleMeetService)
    {
    }


    public static function getEntityFqcn(): string
    {
        return Interview::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Entretien')
            ->setEntityLabelInPlural('Entretiens')
            ->setEntityPermission('ROLE_ADMIN')
        ;
    }
    

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('candidate'),
            TextField::new('candidate.offre.title')->setLabel('Offre')->hideOnForm(),
            DateTimeField::new('interviewDate')->setLabel('Date de l\'entretien'),
            UrlField::new('location')->setLabel('Lien de l\'entretien')
                ->hideOnForm()
                ->setHelp('Le lien de l\'entretien sera généré automatiquement'),
            ChoiceField::new('status')->hideWhenCreating()->setLabel('Statut de l\'entretien')->setChoices([
                Interview::STATUS_PENDING => 'En attente',
                Interview::STATUS_ACCEPTED => 'Accepté',
                Interview::STATUS_REFUSED => 'Refusé',
                Interview::STATUS_CANCELLED => 'Annulé',
                Interview::STATUS_COMPLETED => 'Terminé',
                Interview::STATUS_NO_SHOW => 'Non présenté',
            ])->renderAsBadges([
                Interview::STATUS_PENDING => 'secondary',
                Interview::STATUS_ACCEPTED => 'success',
                Interview::STATUS_REFUSED => 'danger',
                Interview::STATUS_CANCELLED => 'warning',
                Interview::STATUS_COMPLETED => 'info',
                Interview::STATUS_NO_SHOW => 'dark',
            ])->setCustomOption('help', 'Le statut de l\'entretien est mis à jour automatiquement selon le statut du candidat.'),
            TextEditorField::new('notes')->setFormTypeOptions(['required' => false])


        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Interview) {
            return;
        }

        // Create Google Meet link
        $meetLink = $this->googleMeetService->createMeeting(
            'Entretient avec ' . $entityInstance->getCandidate()->getUser()->getFirstName(),
            $entityInstance->getCandidate()->getUser()->getEmail(),
            $entityInstance->getInterviewDate(),
        );
        
        // Set the meet link
        $entityInstance->setLocation($meetLink);

        // Save the entity
        parent::persistEntity($entityManager, $entityInstance);

        // Send email to candidate
        $this->emailSender->sendEmail(
            'noreply@nouvsys.fr',
            $entityInstance->getCandidate()->getUser()->getEmail(),
            'Entretien programmé pour le poste de ' . $entityInstance->getCandidate()->getOffre()->getTitle(),
            'emails/interview_notification.html.twig',
            [
                'candidate' => $entityInstance->getCandidate(),
                'interview' => $entityInstance->getInterviewDate(),
                'meetLink' => $meetLink,
            ]
        );
     
    }
}
