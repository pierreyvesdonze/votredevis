<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Estimate::class,
    //     ]);
    // }
}
