<?php

namespace App\Service;

use App\Entity\Warehouse;
use Doctrine\ORM\EntityManagerInterface;

class WarehouseService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function create(Warehouse $warehouse): Warehouse
    {
        $this->em->persist($warehouse);
        $this->em->flush();

        return $warehouse;
    }

    public function update()
    {
        $this->em->flush();
    }

    public function remove(Warehouse $warehouse)
    {
        $this->em->remove($warehouse);
        $this->em->flush();
    }
}
