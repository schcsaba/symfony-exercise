<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use App\Form\RoleType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
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
            TextField::new('username', new TranslatableMessage('easyadmin.username')),
            Field::new('password', new TranslatableMessage('easyadmin.password'))
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
            ChoiceField::new('roles', new TranslatableMessage('easyadmin.roles'))
                ->setChoices([
                    'easyadmin.company' => 'ROLE_USER',
                    'easyadmin.admin' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(false)
                ->renderExpanded()
                ->setFormType(RoleType::class)
                ->setPermission('ROLE_ADMIN'),
            AssociationField::new('company', new TranslatableMessage('easyadmin.company'))
                ->setPermission('ROLE_ADMIN')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $impersonate = Action::new('impersonate', false, 'fa fa-fw fa-user-lock')
            ->linkToUrl(function (User $entity) {
                return 'admin/?_switch_user=' . $entity->getUserIdentifier();
            });

        $actions = parent::configureActions($actions);
        if ($this->isGranted('ROLE_ADMIN')) {
            $actions->add(Crud::PAGE_INDEX, $impersonate);
        }

        $actions->setPermission(Action::NEW, 'ROLE_ADMIN');
        $actions->setPermission(Action::DELETE, 'ROLE_ADMIN');

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('easyadmin.user')
            ->setEntityLabelInPlural('easyadmin.users')
            ->setEntityPermission('ADMIN_USER_EDIT');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->isGranted('ROLE_ADMIN')) {
            return $queryBuilder;
        }

        $queryBuilder
            ->andWhere('entity.id = :id')
            ->setParameter('id', $this->getUser()->getId());

        return $queryBuilder;
    }
}
