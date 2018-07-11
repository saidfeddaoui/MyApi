<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractRepository")
 */
class Contract
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $contract_number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Predeclaration", mappedBy="contract")
     */
    private $predeclarations;

    public function __construct()
    {
        $this->predeclarations = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContractNumber(): ?string
    {
        return $this->contract_number;
    }

    public function setContractNumber(string $contract_number): self
    {
        $this->contract_number = $contract_number;

        return $this;
    }

    /**
     * @return Collection|Predeclaration[]
     */
    public function getPredeclarations(): Collection
    {
        return $this->predeclarations;
    }

    public function addPredeclaration(Predeclaration $predeclaration): self
    {
        if (!$this->predeclarations->contains($predeclaration)) {
            $this->predeclarations[] = $predeclaration;
            $predeclaration->setContract($this);
        }

        return $this;
    }

    public function removePredeclaration(Predeclaration $predeclaration): self
    {
        if ($this->predeclarations->contains($predeclaration)) {
            $this->predeclarations->removeElement($predeclaration);
            // set the owning side to null (unless already changed)
            if ($predeclaration->getContract() === $this) {
                $predeclaration->setContract(null);
            }
        }

        return $this;
    }
}
