<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eset_guid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eset_key;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="company")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="company")
     */
    private $payments;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->payments = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getEsetGuid(): ?string
    {
        return $this->eset_guid;
    }

    public function setEsetGuid(?string $eset_guid): self
    {
        $this->eset_guid = $eset_guid;

        return $this;
    }

    public function getEsetKey(): ?string
    {
        return $this->esset_key;
    }

    public function setEsetKey(?string $eset_key): self
    {
        $this->eset_key = $eset_key;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCompany($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCompany() === $this) {
                $order->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setCompany($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getCompany() === $this) {
                $payment->setCompany(null);
            }
        }

        return $this;
    }
}
