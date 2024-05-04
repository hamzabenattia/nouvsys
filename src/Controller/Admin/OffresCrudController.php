<?php

namespace App\Controller\Admin;

use App\Entity\Offres;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]

class OffresCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offres::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title')->setLabel('Titre'),
            ChoiceField::new('category')->setChoices([
                'Développement' => 'Développement',
                'Design' => 'Design',
                'Test' => 'Test',
                'Support' => 'Support',
                'Autre' => 'Autre',
            ])->setLabel('Catégorie'),
            ChoiceField::new('type')->setChoices([
               'Temps plein' => 'Temps plein',
                'Temps partiel' => 'Temps partiel',
                'CDD' => 'CDD',
                'CDI' => 'CDI',
                'Freelance' => 'Freelance',
                'Stage' => 'Stage',
                'Alternance' => 'Alternance',
                'Autre' => 'Autre'
            ])->setLabel('Type de contrat'),
            ChoiceField::new('location')->setChoices([
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
            ])->setLabel('Lieu de travail'),
            TextEditorField::new('description'),

            DateField::new('createdAt')->setLabel('Date de création')->hideOnForm(),
            AssociationField::new('candidates')->hideOnForm()->setLabel('N° Candidats')

            
        ];
    }


    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add(ChoiceFilter::new('category')->setChoices([
                'Développement' => 'Développement',
                'Design' => 'Design',
                'Test' => 'Test',
                'Support' => 'Support',
                'Autre' => 'Autre',
            ]))
            ->add(ChoiceFilter::new('type')->setChoices([
                'Temps plein' => 'Temps plein',
                'Temps partiel' => 'Temps partiel',
                'CDD' => 'CDD',
                'CDI' => 'CDI',
                'Freelance' => 'Freelance',
                'Stage' => 'Stage',
                'Alternance' => 'Alternance',
                'Autre' => 'Autre'
            ]))
            ->add(ChoiceFilter::new('location')->setChoices([
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

            ->add(DateTimeFilter::new('createdAt', 'Date de postulation'))
        
        ;
    }
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
           
        ;
        
    }
}
