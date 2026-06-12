<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Stock;
use App\Entity\Warehouse;
use App\Repository\WarehouseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $excluded = $options['excluded_warehouses'];

        $builder
            ->add('quantity', NumberType::class)
            ->add('warehouse', EntityType::class, [
                'class' => Warehouse::class,
                'choice_label' => 'name',
                'query_builder' => function(WarehouseRepository $repository) use ($excluded) {
                    $qb = $repository->createQueryBuilder('w');

                     if(!empty($excluded)) {
                        $qb->where('w.id NOT IN (:ids)')
                        ->setParameter('ids', $excluded);
                    }

                    return $qb;
                }
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
            'excluded_warehouses' => [],
        ]);
    }
}
