<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2)]
    private string $name;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2)]
    private string $address;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 10)]
    #[Assert\Regex(pattern: '/^[0-9]+$/')]
    private string $phone;

    /**
     * @var Collection<int, SalesOrder>
     */
    #[ORM\OneToMany(targetEntity: SalesOrder::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $salesOrders;

    public function __construct()
    {
        $this->salesOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, SalesOrder>
     */
    public function getSalesOrders(): Collection
    {
        return $this->salesOrders;
    }

    public function addSalesOrder(SalesOrder $salesOrder): static
    {
        if (!$this->salesOrders->contains($salesOrder)) {
            $this->salesOrders->add($salesOrder);
            $salesOrder->setCustomer($this);
        }

        return $this;
    }

    public function removeSalesOrder(SalesOrder $salesOrder): static
    {
        if ($this->salesOrders->removeElement($salesOrder)) {
            // set the owning side to null (unless already changed)
            if ($salesOrder->getCustomer() === $this) {
                $salesOrder->setCustomer(null);
            }
        }

        return $this;
    }
}
