<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{   
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="product")
     */
    private $orderLines;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ProductCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showAsProduct;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|OrderLine[]
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $orderLine->setProduct($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getProduct() === $this) {
                $orderLine->setProduct(null);
            }
        }

        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->ProductCode;
    }

    public function setProductCode(?string $ProductCode): self
    {
        $this->ProductCode = $ProductCode;

        return $this;
    }

    public function getShowAsProduct(): ?bool
    {
        return $this->showAsProduct;
    }

    public function setShowAsProduct(?bool $showAsProduct): self
    {
        $this->showAsProduct = $showAsProduct;

        return $this;
    }
}
