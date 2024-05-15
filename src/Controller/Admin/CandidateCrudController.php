<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            TextField::new('user.firstName')->setLabel('Prénom'),
            TextField::new('user.lastName')->setLabel('Nom'),
            TextField::new('user.email')->setLabel('Email'),
            TextField::new('user.phoneNumber')->setLabel('Téléphone'),
            TextEditorField::new('message')->setLabel('Message'),
            AssociationField::new('offre')->autocomplete(),
            DateField::new('createdAt')->setLabel('Date de postulation'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('createdAt', 'Date de postulation'))
            ->add(EntityFilter::new('offre', 'Offre'))
            ->add(EntityFilter::new('user','Candidate'));
    }
}
