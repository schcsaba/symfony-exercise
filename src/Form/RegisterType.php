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
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => new TranslatableMessage('account.username'),
                'attr' => [
                    'placeholder' => new TranslatableMessage('johndoe')
                ],
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ])
            ])
            ->add('password', RepeatedType::class, [
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
            ->add('submit', SubmitType::class, [
                'label' => new TranslatableMessage('account.register')
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
