<?php

namespace App\Controller\Admin;

use App\Entity\Interview;
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
            DateTimeField::new('interviewDate')->setLabel('Date de l\'entretien'),
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
            UrlField::new('location')->setLabel('Lien de l\'entretien')->setHelp('Le lien de l\'entretien doit être au format URL(Google meet, Zoom, teams, etc.)'),
            TextEditorField::new('notes')->setFormTypeOptions(['required' => false])


        ];
    }

}
