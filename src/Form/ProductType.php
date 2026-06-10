<?php

namespace App\Form;

use App\Entity\Product;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ProductType extends AbstractType
{
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
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setDates(...))
        ;
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['name'])->toString());
            $event->setData($data);
        }
    }

    public function setDates(PostSubmitEvent $event): void
    {
        $product = $event->getData();

        $product->setUpdatedAt(new DateTimeImmutable());
        if (!$product->getId()) {
            $product->setCreatedAt(new DateTimeImmutable());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
