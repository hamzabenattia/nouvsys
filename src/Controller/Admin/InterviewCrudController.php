<?php

namespace App\Controller\Admin;

use App\Entity\Interview;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InterviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Interview::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('candidate'),
            DateTimeField::new(propertyName: 'interviewDate'),
            TextField::new('status'),
            TextEditorField::new('notes'),
            TextField::new('location'),
        ];
    }

}
