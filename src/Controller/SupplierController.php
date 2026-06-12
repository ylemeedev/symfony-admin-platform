<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Form\SupplierType;
use App\Repository\SupplierRepository;
use App\Service\SupplierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/supplier', name: 'supplier.')]
final class SupplierController extends AbstractController
{
    public function __construct(private SupplierRepository $supplierRepository, private SupplierService $supplierService)
    {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $suppliers = $this->supplierRepository->findAll();

        return $this->render('supplier/index.html.twig', [
            'suppliers' => $suppliers,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request)
    {
        $supplier = new Supplier();

        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->supplierService->create($supplier);

            $this->addFlash('success', 'Le fournissur a été ajouté');

            return $this->redirectToRoute('supplier.index');
        }

        return $this->render('supplier/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, Supplier $supplier)
    {
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->supplierService->update();

            $this->addFlash('success', 'Le fournissur a été ajouté');

            return $this->redirectToRoute('supplier.index');
        }

        return $this->render('supplier/edit.html.twig', [
            'supplier' => $supplier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Supplier $supplier): Response
    {
        $this->supplierService->remove($supplier);

        $this->addFlash('success', 'L\'entrepôt à été supprimé');

        return $this->redirectToRoute('supplier.index');
    }
}
