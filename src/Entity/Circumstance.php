<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CircumstanceRepository")
 * @Serializer\ExclusionPolicy("all")
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
     * @var double
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotBlank(groups={"client_pre_declaration"})
     * @Assert\Regex(pattern="/^\-?\d+(\.\d+)?$/", groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var double
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotBlank(groups={"client_pre_declaration"})
     * @Assert\Regex(pattern="/^\-?\d+(\.\d+)?$/", groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @var Ville
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\Type(
     *     type="bool",
     *     message="The value {{ value }} is not a valid {{ type }}.",
     *     groups={"client_pre_declaration"}
     * )
     *
     * @ORM\Column(type="boolean")
     */
    private $remorquage;

    /**
     * @var Collection
     *
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\CircumstanceAttachment", mappedBy="circumstance", cascade={"persist"})
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

    /**
     * @param mixed $photos
     * @return Circumstance
     */
    public function setPhotos($photos)
    {
        foreach ($this->photos as $photo) {
            $this->removePhoto($photo);
        }
        foreach ($photos as $photo) {
            $this->addPhoto($photo);
        }
        return $this;
    }


    public function addPhoto(CircumstanceAttachment $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setCircumstance($this);
        }

        return $this;
    }

    public function removePhoto(CircumstanceAttachment $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getCircumstance() === $this) {
                $photo->setCircumstance(null);
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
        if ($newCircumstance !== $preDeclaration->getCircumstance()) {
            $preDeclaration->setCircumstance($newCircumstance);
        }

        return $this;
    }

}
