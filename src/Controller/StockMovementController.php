<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StockMovementController extends AbstractController
{
    #[Route('/stock/movement', name: 'stock_movement.index')]
    public function index(): Response
    {
        return $this->render('stock_movement/index.html.twig', [
            'controller_name' => 'StockMovementController',
        ]);
    }
}
