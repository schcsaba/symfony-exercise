<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
            TextField::new('username')->setDisabled(),
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
}
