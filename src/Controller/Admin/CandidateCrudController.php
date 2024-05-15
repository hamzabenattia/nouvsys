<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Choice;

#[IsGranted('ROLE_ADMIN')]
class CandidateCrudController extends AbstractCrudController
{
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
            ->add(Crud::PAGE_DETAIL, $viewCV);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setDisabled(),
            TextField::new('user.firstName')->setLabel('Prénom')->setDisabled(),
            TextField::new('user.lastName')->setLabel('Nom')->setDisabled(),
            TextField::new('user.email')->setLabel('Email')->setDisabled(),
            TextField::new('user.phoneNumber')->setLabel('Téléphone')->setDisabled(),
            TextEditorField::new('message')->setLabel('Message')->setDisabled(),
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
            AssociationField::new('offre')->autocomplete(),
            DateField::new('createdAt')->setLabel('Date de postulation'),
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
}
