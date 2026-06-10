<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PurchaseOrderController extends AbstractController
{
    #[Route('/purchase/order', name: 'purchase_order.index')]
    public function index(): Response
    {
        return $this->render('purchase_order/index.html.twig', [
            'controller_name' => 'PurchaseOrderController',
        ]);
    }
}
