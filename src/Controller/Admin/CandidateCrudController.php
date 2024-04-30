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
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

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
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, $viewCV)
            ->add(Crud::PAGE_DETAIL, $viewCV);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('firstName')->setLabel('Prénom'),
            TextField::new('lastName')->setLabel('Nom'),
            TextField::new('email')->setLabel('Email'),
            TextField::new('phoneNumber')->setLabel('Téléphone'),
            TextEditorField::new('message')->setLabel('Message'),
            ChoiceField::new('type')->setChoices([
                Candidate::TYPE_OFFER => Candidate::TYPE_OFFER,
                Candidate::TYPE_SPONTANEOUS => Candidate::TYPE_SPONTANEOUS,
            ])->renderAsBadges([
                Candidate::TYPE_OFFER => 'primary',
                Candidate::TYPE_SPONTANEOUS => 'secondary',
            ])->hideOnForm(),
            AssociationField::new('offre')->autocomplete(),

            DateField::new('createdAt')->setLabel('Date de postulation'),





        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('type')->setChoices([
                Candidate::TYPE_OFFER => Candidate::TYPE_OFFER,
                Candidate::TYPE_SPONTANEOUS => Candidate::TYPE_SPONTANEOUS,
            ]))
            ->add(DateTimeFilter::new('createdAt', 'Date de postulation'))
            ->add('email')
            ->add('phoneNumber')
            ->add('offre');
    }
}
