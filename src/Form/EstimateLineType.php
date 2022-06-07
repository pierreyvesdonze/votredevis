<?php

namespace App\Form;

use App\Entity\EstimateLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstimateLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'attr' => [
                    'placeholder' => 'Description'
                ]
            ])
            ->add('date', DateType::class, [
                'attr' => [
                    'placeholder' => 'Date'
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Quantité'
                ]
            ])
            ->add('price', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Prix HT'
                ]
            ])
            ->add('tva', NumberType::class, [
                'attr' => [
                    'placeholder' => 'TVA'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EstimateLine::class,
        ]);
    }
}
