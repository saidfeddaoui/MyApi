<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CircumstanceRepository")
 */
class Circumstance
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
     * @ORM\OneToMany(targetEntity="App\Entity\CircumstanceAttachment", mappedBy="circumstance")
     */
    private $photos;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PreDeclaration", mappedBy="circumstance", cascade={"persist", "remove"})
     */
    private $preDeclaration;

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
     * @return Collection|CircumstanceAttachment[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(CircumstanceAttachment $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setCirconstance($this);
        }

        return $this;
    }

    public function removePhoto(CircumstanceAttachment $photo): self
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

    public function getPreDeclaration(): ?PreDeclaration
    {
        return $this->preDeclaration;
    }

    public function setPreDeclaration(?PreDeclaration $preDeclaration): self
    {
        $this->preDeclaration = $preDeclaration;

        // set (or unset) the owning side of the relation if necessary
        $newCircumstance = $preDeclaration === null ? null : $this;
        if ($newCircumstance !== $preDeclaration->getCirconstance()) {
            $preDeclaration->setCirconstance($newCircumstance);
        }

        return $this;
    }
}
