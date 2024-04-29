<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            ->add(Crud::PAGE_DETAIL,$viewCV )
            ;
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
            DateField::new('createdAt')->setLabel('Date de création'),
          

            

        ];
    }
    
//     public function configureCrud(Crud $crud): Crud
// {
//     return $crud
//         // other configurations...
//         ->overrideTemplate('crud/detail', 'admin/candidate_detail.html.twig');
// }
    
}
