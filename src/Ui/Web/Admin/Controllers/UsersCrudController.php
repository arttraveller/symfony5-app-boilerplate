<?php

namespace App\Ui\Web\Admin\Controllers;

use App\Core\Entities\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            EmailField::new('email'),
            TextField::new('name')->formatValue(
                fn($value, $entity) => $entity->getName()->getFullName()
            ),
            IntegerField::new('status')->formatValue(
                fn($value, $entity) => $entity->isActive() ? 'Active' : 'Not confirmed',
            ),
            DateTimeField::new('createdAt')->formatValue(
                fn($value, $entity) => $entity->getCreatedAt()->format('d.m.Y')
            ),
        ];
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(
            Action::EDIT,
            Action::NEW,
            Action::DELETE
        );
    }

}
