<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class EstimateFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                        'Plus récents' => 1,
                        'Plus anciens' => 2
                    ],
                    'attr' => [
                        'class' => 'input-field col s4'
                    ],
                    'label' => false
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Filtrer',
                    'attr' => [
                        'class' => 'custom-btn',
                    ],
                ]
            );
    }
}
