<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\GarageRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Garage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("garage")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raison_sociale;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("garage")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $adresse;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("garage")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom_ville;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("garage")
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $tel;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("garage")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InsuranceType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $insuranceType;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("garage")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coordinates;

    public function getId()
    {
        return $this->id;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raison_sociale;
    }

    public function setRaisonSociale(?string $raison_sociale): self
    {
        $this->raison_sociale = $raison_sociale;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNomVille(): ?string
    {
        return $this->nom_ville;
    }

    public function setNomVille(?string $nom_ville): self
    {
        $this->nom_ville = $nom_ville;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(?string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getInsuranceType(): ?InsuranceType
    {
        return $this->insuranceType;
    }

    public function setInsuranceType(?InsuranceType $insuranceType): self
    {
        $this->insuranceType = $insuranceType;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }
}
