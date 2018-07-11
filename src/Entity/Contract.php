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
     * @ORM\OneToMany(targetEntity="App\Entity\PreDeclaration", mappedBy="contract")
     */
    private $preDeclarations;

    public function __construct()
    {
        $this->preDeclarations = new ArrayCollection();
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
     * @return Collection|PreDeclaration[]
     */
    public function getPreDeclarations(): Collection
    {
        return $this->preDeclarations;
    }

    public function addPreDeclaration(PreDeclaration $preDeclaration): self
    {
        if (!$this->preDeclarations->contains($preDeclaration)) {
            $this->preDeclarations[] = $preDeclaration;
            $preDeclaration->setContract($this);
        }

        return $this;
    }

    public function removePreDeclaration(PreDeclaration $preDeclaration): self
    {
        if ($this->preDeclarations->contains($preDeclaration)) {
            $this->preDeclarations->removeElement($preDeclaration);
            // set the owning side to null (unless already changed)
            if ($preDeclaration->getContract() === $this) {
                $preDeclaration->setContract(null);
            }
        }

        return $this;
    }

}
