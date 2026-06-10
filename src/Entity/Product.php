<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use App\Validator\ValidProductName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity('name')]
#[UniqueEntity('reference')]
#[UniqueEntity('slug')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, minMessage: 'La référence doit comporter plus de 2 caratères')]
    private ?string $reference = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, minMessage: 'Le nom doit comporter plus de 2 caratères')]
    #[ValidProductName(forbiddenWords: ['spam', 'pub', 'viagra'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank()]
    #[Assert\PositiveOrZero(message: 'La valeur ne peut pas être négative')]
    private ?string $price = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[PositiveOrZero(message: 'La valeur ne peut pas être négative')]
    private ?int $minimumStock = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Stock>
     */
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $stocks;

    /**
     * @var Collection<int, StockMovement>
     */
    #[ORM\OneToMany(targetEntity: StockMovement::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $stockMovements;

    /**
     * @var Collection<int, PurchaseOrderLine>
     */
    #[ORM\OneToMany(targetEntity: PurchaseOrderLine::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $purchaseOrderLines;

    /**
     * @var Collection<int, SalesOrderLine>
     */
    #[ORM\OneToMany(targetEntity: SalesOrderLine::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $salesOrderLines;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->stockMovements = new ArrayCollection();
        $this->purchaseOrderLines = new ArrayCollection();
        $this->salesOrderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getMinimumStock(): ?int
    {
        return $this->minimumStock;
    }

    public function setMinimumStock(int $minimumStock): static
    {
        $this->minimumStock = $minimumStock;

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setProduct($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProduct() === $this) {
                $stock->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StockMovement>
     */
    public function getStockMovements(): Collection
    {
        return $this->stockMovements;
    }

    public function addStockMovement(StockMovement $stockMovement): static
    {
        if (!$this->stockMovements->contains($stockMovement)) {
            $this->stockMovements->add($stockMovement);
            $stockMovement->setProduct($this);
        }

        return $this;
    }

    public function removeStockMovement(StockMovement $stockMovement): static
    {
        if ($this->stockMovements->removeElement($stockMovement)) {
            // set the owning side to null (unless already changed)
            if ($stockMovement->getProduct() === $this) {
                $stockMovement->setProduct(null);
            }
        }

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
            $purchaseOrderLine->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseOrderLine(PurchaseOrderLine $purchaseOrderLine): static
    {
        if ($this->purchaseOrderLines->removeElement($purchaseOrderLine)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrderLine->getProduct() === $this) {
                $purchaseOrderLine->setProduct(null);
            }
        }

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
            $salesOrderLine->setProduct($this);
        }

        return $this;
    }

    public function removeSalesOrderLine(SalesOrderLine $salesOrderLine): static
    {
        if ($this->salesOrderLines->removeElement($salesOrderLine)) {
            // set the owning side to null (unless already changed)
            if ($salesOrderLine->getProduct() === $this) {
                $salesOrderLine->setProduct(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
