<?php

namespace App\Form;

use App\Entity\Candidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\Length;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => new TranslatableMessage('easyadmin.candidate.firstname'),
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ])
            ])
            ->add('lastname', TextType::class, [
                'label' => new TranslatableMessage('easyadmin.candidate.lastname'),
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 255
                ])
            ])
            ->add('phone', TelType::class, [
                'label' => new TranslatableMessage('easyadmin.candidate.phone'),
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => new TranslatableMessage('easyadmin.candidate.email'),
                'required' => true
            ])
            ->add('cv', FileType::class, [
                'label' => new TranslatableMessage('easyadmin.candidate.cv'),
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => new TranslatableMessage('candidate.send')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
