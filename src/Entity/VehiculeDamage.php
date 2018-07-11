<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehiculeDamageRepository")
 */
class VehiculeDamage
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\VehiculeComponent")
     */
    private $damagedParts;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PreDeclaration", mappedBy="vehiculeDamage", cascade={"persist", "remove"})
     */
    private $preDeclaration;

    public function __construct()
    {
        $this->damagedParts = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection|VehiculeComponent[]
     */
    public function getDamagedParts(): Collection
    {
        return $this->damagedParts;
    }

    public function addDamagedPart(VehiculeComponent $damagedPart): self
    {
        if (!$this->damagedParts->contains($damagedPart)) {
            $this->damagedParts[] = $damagedPart;
        }

        return $this;
    }

    public function removeDamagedPart(VehiculeComponent $damagedPart): self
    {
        if ($this->damagedParts->contains($damagedPart)) {
            $this->damagedParts->removeElement($damagedPart);
        }

        return $this;
    }

    public function getPreDeclaration(): ?PreDeclaration
    {
        return $this->preDeclaration;
    }

    public function setPreDeclaration(?PreDeclaration $preDeclaration): self
    {
        $this->preDeclaration = $preDeclaration;

        // set (or unset) the owning side of the relation if necessary
        $newVehiculeDamage = $preDeclaration === null ? null : $this;
        if ($newVehiculeDamage !== $preDeclaration->getVehiculeDamage()) {
            $preDeclaration->setVehiculeDamage($newVehiculeDamage);
        }

        return $this;
    }

}
