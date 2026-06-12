<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Stock;
use App\Form\StockType;
use App\Form\StockUpdateType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/stock', name: 'stock.')]
final class StockController extends AbstractController
{
    public function __construct(private StockRepository $stockRepository)
    {
    }

    #[Route('/create/product/{id}', name: 'create', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function create(Request $request, EntityManagerInterface $em, Product $product): Response
    {
        $stock = new Stock();

        $existingWarehouses = array_map(
            fn($stock) => $stock->getWarehouse()->getId(),
            $product->getStocks()->toArray()
        );

        $form = $this->createForm(StockType::class, $stock, [
            'excluded_warehouses' => $existingWarehouses
        ]);

        $stock->setProduct($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($stock);
            $em->flush();

            $this->addFlash('success', 'Le stock est a jour');

            return $this->redirectToRoute('product.detail', [
                'id' => $stock->getProduct()->getId(),
                'slug' => $stock->getProduct()->getSlug(),
            ]);
        }

        return $this->render('stock/create.html.twig', [
            'form' => $form,
            'product' => $product,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, Stock $stock, EntityManagerInterface $em)
    {
        $stockByWarehouse = $this->stockRepository->findBy(['product' => $stock->getProduct()]);

        $form = $this->createForm(StockUpdateType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($stock);
            $em->flush();

            $this->addFlash('success', 'Le stock à été modifié');

            return $this->redirectToRoute('product.detail', [
                'id' => $stock->getProduct()->getId(),
                'slug' => $stock->getProduct()->getSlug(),
            ]);
        }

        return $this->render('stock/edit.html.twig', [
            'stockByWarehouse' => $stockByWarehouse,
            'stock' => $stock,
            'form' => $form,
        ]);
    }
}
