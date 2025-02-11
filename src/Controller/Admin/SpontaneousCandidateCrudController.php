<?php

namespace App\Controller\Admin;

use App\Entity\SpontaneousCandidate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class SpontaneousCandidateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SpontaneousCandidate::class;
    }


    public function configureCrud(Crud $crud): Crud
{
    return $crud
        // the labels used to refer to this entity in titles, buttons, etc.
        ->setEntityLabelInSingular('Candidature spontanée')
        ->setEntityLabelInPlural('Candidatures spontanées')
        ->setEntityPermission('ROLE_ADMIN')
    ;
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
            TextField::new('ville')->setLabel('Ville'),
            TextField::new('educationLevel')->setLabel('Niveau d\'étude'),
            TextField::new('fonction')->setLabel('Fonction'),
            TextField::new('experience')->setLabel('Expérience'),
            TextEditorField::new('message')->setLabel('Message'),
            DateField::new('createdAt')->setLabel('Date de postulation'),

        ];
    }
 

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('createdAt', 'Date de postulation'))
            ->add(TextFilter::new('email', 'Email'))
            ->add('phoneNumber','Téléphone')
            ->add(ChoiceFilter::new('ville')->setChoices([
                'Paris' => 'Paris',
                'Lyon' => 'Lyon',
                'Marseille' => 'Marseille',
                'Bordeaux' => 'Bordeaux',
                'Lille' => 'Lille',
                'Toulouse' => 'Toulouse',
                'Nantes' => 'Nantes',
                'Strasbourg' => 'Strasbourg',
                'Montpellier' => 'Montpellier',
                'Rennes' => 'Rennes',
                'Autre' => 'Autre',
            ]))
            ->add(ChoiceFilter::new('educationLevel')->setChoices([
                'Niveau Bac' => 'Niveau Bac',
                'Licence' => 'Licence',
                'Master' => 'Master',
                'Bac+5' => 'Bac+5',
                'Autre' => 'Autre', 
            ]))
            ->add(ChoiceFilter::new('fonction')->setChoices([
                'Développeur Web' => 'Développeur Web',
                'Développeur Mobile' => 'Développeur Mobile',
                'Designer' => 'Designer',
                'Chef de projet' => 'Chef de projet',
                'Technicien' => 'Technicien',
                'Commercial' => 'Commercial',
                'Testeur' => 'Testeur',
                'Autre' => 'Autre'
            ]))
            ->add(ChoiceFilter::new('experience')->setChoices([
                'Moins de 3 ans' => 'Moins de 3 ans',
                'de 3 à 5 ans' => 'de 3 à 5 ans',
                'de 6 à 10 ans' => 'de 6 à 10 ans',
                'Plus de 10 ans' => 'Plus de 10 ans',
                ]))

        ;
        }



}
