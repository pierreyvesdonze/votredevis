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

    #[ORM\OneToMany(mappedBy: 'estimate', targetEntity: EstimateLine::class)]
    private $estimateLine;

    #[ORM\Column(type: 'date')]
    private $date;

    public function __construct()
    {
        $this->estimateLine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
