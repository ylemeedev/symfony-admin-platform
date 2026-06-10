<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SalesOrderController extends AbstractController
{
    #[Route('/sales/order', name: 'sales_order.index')]
    public function index(): Response
    {
        return $this->render('sales_order/index.html.twig', [
            'controller_name' => 'SalesOrderController',
        ]);
    }
}
