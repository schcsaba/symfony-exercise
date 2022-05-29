<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RoleType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Translation\TranslatableMessage;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            Field::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options' => [
                        'label' => new TranslatableMessage('account.password'),
                        'attr' => [
                            'placeholder' => new TranslatableMessage('account.password.new.cta')
                        ]
                    ],
                    'second_options' => [
                        'label' => new TranslatableMessage('account.password.confirm'),
                        'attr' => [
                            'placeholder' => new TranslatableMessage('account.password.confirm.cta')
                        ]
                    ]
                ])
                ->onlyWhenCreating(),
            ChoiceField::new('roles')
                ->setChoices([
                    'Company' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(false)
                ->renderExpanded()
                ->setFormType(RoleType::class),
            AssociationField::new('company')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_ADMIN');
    }
}
