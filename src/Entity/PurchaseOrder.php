<?php

namespace App\Entity;

use App\Enum\StatusPurchaseOrder;
use App\Repository\PurchaseOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseOrderRepository::class)]
class PurchaseOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateAt = null;

    #[ORM\Column(enumType: StatusPurchaseOrder::class)]
    private ?StatusPurchaseOrder $status = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Supplier $supplier = null;

    /**
     * @var Collection<int, PurchaseOrderLine>
     */
    #[ORM\OneToMany(targetEntity: PurchaseOrderLine::class, mappedBy: 'purchaseOrder', orphanRemoval: true)]
    private Collection $purchaseOrderLines;

    public function __construct()
    {
        $this->purchaseOrderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getDateAt(): ?\DateTimeImmutable
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeImmutable $dateAt): static
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getStatus(): ?StatusPurchaseOrder
    {
        return $this->status;
    }

    public function setStatus(StatusPurchaseOrder $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseOrderLine>
     */
    public function getPurchaseOrderLines(): Collection
    {
        return $this->purchaseOrderLines;
    }

    public function addPurchaseOrderLine(PurchaseOrderLine $purchaseOrderLine): static
    {
        if (!$this->purchaseOrderLines->contains($purchaseOrderLine)) {
            $this->purchaseOrderLines->add($purchaseOrderLine);
            $purchaseOrderLine->setPurchaseOrder($this);
        }

        return $this;
    }

    public function removePurchaseOrderLine(PurchaseOrderLine $purchaseOrderLine): static
    {
        if ($this->purchaseOrderLines->removeElement($purchaseOrderLine)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrderLine->getPurchaseOrder() === $this) {
                $purchaseOrderLine->setPurchaseOrder(null);
            }
        }

        return $this;
    }
}
