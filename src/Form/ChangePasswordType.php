<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => new TranslatableMessage('account.username'),
                'disabled' => true
            ])
            ->add('current_password', PasswordType::class, [
                'label' => new TranslatableMessage('account.password.current'),
                'mapped' => false,
                'attr' => [
                    'placeholder' => new TranslatableMessage('account.password.current.cta')
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'first_options' => [
                    'label' => new TranslatableMessage('account.password.new'),
                    'attr' => [
                        'placeholder' => new TranslatableMessage('account.password.new.cta')
                    ]
                ],
                'second_options' => [
                    'label' => new TranslatableMessage('account.password.new.confirm'),
                    'attr' => [
                        'placeholder' => new TranslatableMessage('account.password.new.confirm.cta')
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => new TranslatableMessage('account.password.change')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
