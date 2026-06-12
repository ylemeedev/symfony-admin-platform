<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{

    public function __construct(private FormListenerFactory $listenerFactory)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('reference', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ex: MC00025'
                ]
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ex: Vis à Tête Cylindrique'
                ]
            ])
            ->add('slug', HiddenType::class)
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('price', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Ex: 2.55'
                ],
            ])
            ->add('minimumStock', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'placeholder' => 'Ex: 10'
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->autoSlug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->listenerFactory->setDates())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
