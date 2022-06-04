<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Translation\TranslatableMessage;

class UserPasswordCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username', new TranslatableMessage('easyadmin.username'))->setDisabled(),
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
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $pass = $entityInstance->getPassword();
        $new_pass = $this->passwordHasher->hashPassword($entityInstance, $pass);

        $entityInstance->setPassword($new_pass);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->disable(Action::NEW, Action::DELETE);

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $singular = $this->isGranted('ROLE_ADMIN') ? 'easyadmin.user.password' : 'easyadmin.mypassword';
        $plural = $this->isGranted('ROLE_ADMIN') ? 'easyadmin.users.passwords' : 'easyadmin.mypassword';
        return $crud
            ->setEntityLabelInSingular($singular)
            ->setEntityLabelInPlural($plural)
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
