<?php

namespace App\Admin\Presentation;

use App\User\Domain\User;
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
            IntegerField::new('status'),
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
