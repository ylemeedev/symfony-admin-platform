<?php

namespace App\Controller;

use App\Entity\Warehouse;
use App\Form\WarehouseType;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/warehouse', name: 'warehouse.')]
final class WarehouseController extends AbstractController
{
    public function __construct(private WarehouseRepository $warehouseRepository)
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
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $warehouse = new Warehouse();

        $form = $this->createForm(WarehouseType::class, $warehouse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $warehouse = $form->getData();
            $em->persist($warehouse);
            $em->flush();

            $this->addFlash('success', 'L\'entrepôt a été ajouté');

            return $this->redirectToRoute('warehouse.detail', ['id' => $warehouse->getId(), 'slug' => $warehouse->getSlug()]);
        }

        return $this->render('warehouse/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}-{slug}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function edit(Warehouse $warehouse, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(WarehouseType::class, $warehouse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'L\'entrepôt a été mis à jour');

            return $this->redirectToRoute('warehouse.detail', ['id' => $warehouse->getId(), 'slug' => $warehouse->getSlug()]);
        }

        return $this->render('warehouse/edit.html.twig', [
            'warehouse' => $warehouse,
            'form' => $form,
        ]);
    }

/*     #[Route('/disabled/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function disabled(Warehouse $warehouse, EntityManagerInterface $em): Response
    {


        return $this->redirectToRoute('warehouse.index');
    } */

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Warehouse $warehouse, EntityManagerInterface $em): Response
    {
        $em->remove($warehouse);
        $em->flush();

        $this->addFlash('success', 'L\'entrepôt à été supprimé');

        return $this->redirectToRoute('warehouse.index');
    }
}
