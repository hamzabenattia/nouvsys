<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Offres;
use Doctrine\ORM\Mapping\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


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
            AssociationField::new('category')
                ->setLabel('Catégorie')
                    ->setHelp('Si la catégorie n\'existe pas, <a href="' .
                    $this->generateUrl('admin', [
                        'crudAction' => 'new',
                        'crudControllerFqcn' => CategoryCrudController::class,
                    ]) . '" target="_blank">cliquez ici pour en ajouter une nouvelle</a>. <br>Après l\'ajout, rafraîchissez cette page pour la sélectionner.'),
  
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
            AssociationField::new('location')
                ->setHelp('Si le lieu de travail n\'existe pas, <a href="' .
                $this->generateUrl('admin', [
                    'crudAction' => 'new',
                    'crudControllerFqcn' => LocationCrudController::class,
                ]) . '" target="_blank">cliquez ici pour en ajouter une nouvelle</a>. <br>Après l\'ajout, rafraîchissez cette page pour la sélectionner.')
                ->setLabel('Lieu de travail'),
            TextEditorField::new('description'),
            DateField::new('createdAt')->setLabel('Date de création')->hideOnForm(),
            AssociationField::new('candidates')->hideOnForm()->setLabel('N° Candidats'),
            BooleanField::new('isPublished', 'Publié'),
            

  


            
        ];
    }


    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add(EntityFilter::new('category', 'Catégorie'))

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
            ->add(EntityFilter::new('location', 'Lieu de travail'))


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
