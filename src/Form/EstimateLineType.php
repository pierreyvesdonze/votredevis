<?php

namespace App\Form;

use App\Entity\EstimateLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstimateLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description'

                ]
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'placeholder' => 'Quantité'
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix HT en €',
                'attr' => [
                    'placeholder' => 'Prix HT en €'
                ]
            ])
            ->add('tva', NumberType::class, [
                'label' => 'Montant TVA en €',
                'attr' => [
                    'placeholder' => 'TVA',
                    'class' => 'estimate-tva'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EstimateLine::class,
        ]);
    }
}
