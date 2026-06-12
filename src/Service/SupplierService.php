<?php

namespace App\Service;

use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;

class SupplierService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function create(Supplier $supplier): Supplier
    {
        $this->em->persist($supplier);
        $this->em->flush();

        return $supplier;
    }

    public function update() {
        $this->em->flush();
    }

    public function remove(Supplier $supplier) {
        $this->em->remove($supplier);
        $this->em->flush();
    }
}
