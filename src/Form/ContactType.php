<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Ex: John Doe',
                ],
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Ex: john@exemple.com',
                ],
            ])
            ->add('service', ChoiceType::class, [
                'choices' => [
                    '' => 'contact@demo.fr',
                    'Support' => 'support',
                    'Comptabilité' => 'accounting',
                    'Marketing' => 'marketing',
                ],
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => '',
                'attr' => [
                    'required' => true,
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Envoyer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
