<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\WarehouseRepository;
use App\Service\ProductService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/product', name: 'product.')]
final class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private ProductService $productService)
    {
    }

    #[Route('/', name: 'index')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $products = $paginator->paginate(
            $this->productRepository->findAllWithTotalStock(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/detail/{id}-{slug}', name: 'detail', requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function detail(Product $product): Response
    {
        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $product = $this->productService->create($product);

            $this->addFlash('success', 'Le produit a été ajouté');

            return $this->redirectToRoute('product.detail', ['id' => $product->getId(), 'slug' => $product->getSlug()]);
        }

        return $this->render('product/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}-{slug}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function edit(Request $request, Product $product, WarehouseRepository $warehouseRepository): Response
    {

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productService->update();

            $this->addFlash('success', 'Le produit a été mis à jour');

            return $this->redirectToRoute('product.detail', ['id' => $product->getId(), 'slug' => $product->getSlug()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
            'canAddWarehouse' => $warehouseRepository->count([]) > $product->getStocks()->count(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Product $product): Response
    {
        $this->productService->remove($product);

        $this->addFlash('success', 'Le produit a été supprimé');

        return $this->redirectToRoute('product.index');
    }
}
