<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EstimateType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->token->getToken()->getUser();
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date de réalisation du devis',
            ])
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre du devis'
                ]
            ])
            ->add('customer', EntityType::class, [
                'label' => 'Client',
                'class' => Customer::class,
                'query_builder' => function(CustomerRepository $customerRepository) use ($user) {
                    return $customerRepository->createQueryBuilder('c')
                        ->where('c.user = :uid')
                        ->setParameter('uid', $user)
                        ->orderBy('c.companyName', 'ASC');
                },
                'choice_label' => 'company_name',
                'attr' => [
                    'placeholder' => 'Date'
                ]
            ])
            ->add('estimate_line', CollectionType::class, [
                'entry_type' => EstimateLineType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ajouter une ligne',
                    'class' => 'estimate-line-line'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'custom-btn'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estimate::class
        ]);
    }
}
