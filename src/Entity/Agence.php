<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgenceRepository")
 */
class Agence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("agence")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raison_sociale;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("agence")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $adresse;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("agence")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom_ville;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("agence")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $tel;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("agence")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("agence")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coordinates;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InsuranceType")
     */
    private $insuranceType;

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

    public function getInsuranceType(): ?InsuranceType
    {
        return $this->insuranceType;
    }

    public function setInsuranceType(?InsuranceType $insuranceType): self
    {
        $this->insuranceType = $insuranceType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     * @return Agence
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * @param mixed $responsable
     * @return Agence
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param mixed $coordinates
     * @return Agence
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }


}
