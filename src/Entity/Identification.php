<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdentificationRepository")
 */
class Identification
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $immatriculation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MarqueVehicule")
     */
    private $marque;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ModeleVehicule")
     */
    private $modele;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PreDeclaration", mappedBy="identification", cascade={"persist", "remove"})
     */
    private $preDeclaration;

    public function getId()
    {
        return $this->id;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getIdentity(): ?string
    {
        return $this->identity;
    }

    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function getMarque(): ?MarqueVehicule
    {
        return $this->marque;
    }

    public function setMarque(?MarqueVehicule $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?ModeleVehicule
    {
        return $this->modele;
    }

    public function setModele(?ModeleVehicule $modele): self
    {
        $this->modele = $modele;

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
        $newIdentification = $preDeclaration === null ? null : $this;
        if ($newIdentification !== $preDeclaration->getIdentification()) {
            $preDeclaration->setIdentification($newIdentification);
        }

        return $this;
    }

}
