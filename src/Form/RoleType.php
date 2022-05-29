<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Based on https://github.com/EasyCorp/EasyAdminBundle/issues/3475#issuecomment-812901062
 */
class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function ($originalRoles){
                    return ($originalRoles) ? $originalRoles[0] : null;
                },
                function($submmitedRoles){
                    return array_filter([$submmitedRoles]);
                }
            ))
        ;
    }
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}