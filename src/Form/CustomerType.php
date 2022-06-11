<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName', TextType::class, [
                'label' => 'Nom du client'
            ])
            ->add('street', TextType::class, [
                'label' => 'Numéro et nom de la rue'
            ])
            ->add('postal', IntegerType::class, [
                'label' => 'Code postal'
            ])
            ->add('town', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('siret', TextType::class, [
                'label' => 'Numéro de siret'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
