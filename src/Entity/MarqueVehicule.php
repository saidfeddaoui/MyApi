<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarqueVehiculeRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class MarqueVehicule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ModeleVehicule", mappedBy="marque")
     */
    private $modeleVehicules;

    public function __construct()
    {
        $this->modeleVehicules = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|ModeleVehicule[]
     */
    public function getModeleVehicules(): Collection
    {
        return $this->modeleVehicules;
    }

    public function addModeleVehicule(ModeleVehicule $modeleVehicule): self
    {
        if (!$this->modeleVehicules->contains($modeleVehicule)) {
            $this->modeleVehicules[] = $modeleVehicule;
            $modeleVehicule->setMarque($this);
        }

        return $this;
    }

    public function removeModeleVehicule(ModeleVehicule $modeleVehicule): self
    {
        if ($this->modeleVehicules->contains($modeleVehicule)) {
            $this->modeleVehicules->removeElement($modeleVehicule);
            // set the owning side to null (unless already changed)
            if ($modeleVehicule->getMarque() === $this) {
                $modeleVehicule->setMarque(null);
            }
        }

        return $this;
    }
}
