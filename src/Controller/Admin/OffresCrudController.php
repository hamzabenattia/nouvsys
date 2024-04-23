<?php

namespace App\Controller\Admin;

use App\Entity\Offres;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            TextField::new('title'),
            TextEditorField::new('description'),
            ChoiceField::new('category')->setChoices([
                'Développement' => 'Développement',
                'Design' => 'Design',
                'Test' => 'Test',
                'Support' => 'Support',
                'Autre' => 'Autre',
            ]),
            ChoiceField::new('type')->setChoices([
               'Temps plein' => 'Temps plein',
                'Temps partiel' => 'Temps partiel',
                'CDD' => 'CDD',
                'CDI' => 'CDI',
                'Freelance' => 'Freelance',
                'Stage' => 'Stage',
                'Alternance' => 'Alternance',
                
                'Autre' => 'Autre',
            ]),
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
            ]),

            
        ];
    }
    
}
