<?php

namespace App\Entity;

use App\Enum\StatusSalesOrder;
use App\Repository\SalesOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalesOrderRepository::class)]
class SalesOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $number = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateAt = null;

    #[ORM\ManyToOne(inversedBy: 'salesOrders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(enumType: StatusSalesOrder::class)]
    private ?StatusSalesOrder $status = null;

    /**
     * @var Collection<int, SalesOrderLine>
     */
    #[ORM\OneToMany(targetEntity: SalesOrderLine::class, mappedBy: 'salesOrder', orphanRemoval: true)]
    private Collection $salesOrderLines;

    public function __construct()
    {
        $this->salesOrderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getStatus(): ?StatusSalesOrder
    {
        return $this->status;
    }

    public function setStatus(StatusSalesOrder $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, SalesOrderLine>
     */
    public function getSalesOrderLines(): Collection
    {
        return $this->salesOrderLines;
    }

    public function addSalesOrderLine(SalesOrderLine $salesOrderLine): static
    {
        if (!$this->salesOrderLines->contains($salesOrderLine)) {
            $this->salesOrderLines->add($salesOrderLine);
            $salesOrderLine->setSalesOrder($this);
        }

        return $this;
    }

    public function removeSalesOrderLine(SalesOrderLine $salesOrderLine): static
    {
        if ($this->salesOrderLines->removeElement($salesOrderLine)) {
            // set the owning side to null (unless already changed)
            if ($salesOrderLine->getSalesOrder() === $this) {
                $salesOrderLine->setSalesOrder(null);
            }
        }

        return $this;
    }
}
