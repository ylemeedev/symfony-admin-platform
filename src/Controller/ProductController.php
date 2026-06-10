<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/product', name: 'product.')]
final class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/detail/{id}-{slug}', name: 'detail', requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function detail(int $id): Response
    {
        $product = $this->productRepository->find($id);

        // Si pas de produit, retour à la liste des produits
        if (!$product) {
            return $this->redirectToRoute('product.index');
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Le produit a été ajouté');

            return $this->redirectToRoute('product.detail', ['id' => $product->getId(), 'slug' => $product->getSlug()]);
        }

        return $this->render('product/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}-{slug}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function edit(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $product = $this->productRepository->find($id);

        // Si pas de produit, retour à la liste des produits
        if (!$product) {
            return $this->redirectToRoute('product.index');
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Le produit a été mis à jour');

            return $this->redirectToRoute('product.detail', ['id' => $id, 'slug' => $product->getSlug()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(EntityManagerInterface $em, Product $product): Response
    {
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Le produit a été supprimé');

        return $this->redirectToRoute('product.index');
    }
}
