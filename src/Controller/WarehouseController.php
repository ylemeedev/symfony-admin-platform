<?php

namespace App\Controller;

use App\Entity\Warehouse;
use App\Form\WarehouseType;
use App\Repository\WarehouseRepository;
use App\Service\WarehouseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/warehouse', name: 'warehouse.')]
final class WarehouseController extends AbstractController
{
    public function __construct(private WarehouseRepository $warehouseRepository, private WarehouseService $warehouseService)
    {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $warehouses = $this->warehouseRepository->findAll();

        return $this->render('warehouse/index.html.twig', [
            'warehouses' => $warehouses,
        ]);
    }

    #[Route('/detail/{id}-{slug}', name: 'detail', requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function detail(Warehouse $warehouse): Response
    {
        return $this->render('warehouse/detail.html.twig', [
            'warehouse' => $warehouse,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $warehouse = new Warehouse();

        $form = $this->createForm(WarehouseType::class, $warehouse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->warehouseService->create($warehouse);

            $this->addFlash('success', 'L\'entrepôt a été ajouté');

            return $this->redirectToRoute('warehouse.detail', ['id' => $warehouse->getId(), 'slug' => $warehouse->getSlug()]);
        }

        return $this->render('warehouse/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}-{slug}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function edit(Warehouse $warehouse, Request $request): Response
    {
        $form = $this->createForm(WarehouseType::class, $warehouse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->warehouseService->update();

            $this->addFlash('success', 'L\'entrepôt a été mis à jour');

            return $this->redirectToRoute('warehouse.detail', ['id' => $warehouse->getId(), 'slug' => $warehouse->getSlug()]);
        }

        return $this->render('warehouse/edit.html.twig', [
            'warehouse' => $warehouse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Warehouse $warehouse): Response
    {
        $this->warehouseService->remove($warehouse);

        $this->addFlash('success', 'L\'entrepôt à été supprimé');

        return $this->redirectToRoute('warehouse.index');
    }
}
