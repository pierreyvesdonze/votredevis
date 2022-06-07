<?php

namespace App\Entity;

use App\Repository\EstimateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstimateRepository::class)]
class Estimate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'estimates')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'estimates')]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    #[ORM\OneToMany(mappedBy: 'estimate', targetEntity: EstimateLine::class, orphanRemoval: true, cascade: ['persist'])]
    private $estimateLine;

    public function __construct()
    {
        $this->estimateLine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, EstimateLine>
     */
    public function getEstimateLine(): Collection
    {
        return $this->estimateLine;
    }

    public function addEstimateLine(EstimateLine $estimateLine): self
    {
        if (!$this->estimateLine->contains($estimateLine)) {
            $this->estimateLine[] = $estimateLine;
            $estimateLine->setEstimate($this);
        }

        return $this;
    }

    public function removeEstimateLine(EstimateLine $estimateLine): self
    {
        if ($this->estimateLine->removeElement($estimateLine)) {
            // set the owning side to null (unless already changed)
            if ($estimateLine->getEstimate() === $this) {
                $estimateLine->setEstimate(null);
            }
        }

        return $this;
    }
}
