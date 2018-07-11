<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CirconstanceRepository")
 */
class Circonstance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\Column(type="boolean")
     */
    private $remorquage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CirconstanceAttachment", mappedBy="circonstance")
     */
    private $photos;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Predeclaration", mappedBy="Circonstance", cascade={"persist", "remove"})
     */
    private $predeclaration;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getRemorquage(): ?bool
    {
        return $this->remorquage;
    }

    public function setRemorquage(bool $remorquage): self
    {
        $this->remorquage = $remorquage;

        return $this;
    }

    /**
     * @return Collection|CirconstanceAttachment[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(CirconstanceAttachment $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setCirconstance($this);
        }

        return $this;
    }

    public function removePhoto(CirconstanceAttachment $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getCirconstance() === $this) {
                $photo->setCirconstance(null);
            }
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
        $newCirconstance = $predeclaration === null ? null : $this;
        if ($newCirconstance !== $predeclaration->getCirconstance()) {
            $predeclaration->setCirconstance($newCirconstance);
        }

        return $this;
    }
}
