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
     * @ORM\OneToOne(targetEntity="App\Entity\Predeclaration", mappedBy="vehiculeDamage", cascade={"persist", "remove"})
     */
    private $predeclaration;

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

    public function getPredeclaration(): ?Predeclaration
    {
        return $this->predeclaration;
    }

    public function setPredeclaration(?Predeclaration $predeclaration): self
    {
        $this->predeclaration = $predeclaration;

        // set (or unset) the owning side of the relation if necessary
        $newVehiculeDamage = $predeclaration === null ? null : $this;
        if ($newVehiculeDamage !== $predeclaration->getVehiculeDamage()) {
            $predeclaration->setVehiculeDamage($newVehiculeDamage);
        }

        return $this;
    }


}
