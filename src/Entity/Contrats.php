<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ContratsRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Contrats
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"contrats","listPreDeclaration","client_pre_declaration"})
     *
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=50)
     * @Serializer\Expose()
     * @Serializer\Groups("contrats")
     */
    private $nomProduit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"contrats","listPreDeclaration","client_pre_declaration"})
     */
    private $police;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="idSocietaire",cascade={"persist", "remove"})
     */
    private $client;

    /**
     * @ORM\Column(type="integer")
     */
    private $idSocietaire;

    /**
     * @ORM\Column(type="string", length=50)
     * @Serializer\Expose()
     * @Serializer\Groups("contrats")
     */
    private $statut;

    /**
     * @ORM\Column(type="date")
     * @Serializer\Expose()
     * @Serializer\Groups("contrats")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private $dateEcheance;


    /**
     * @ORM\Column(type="boolean")
     *
     */
    private $actif;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code;


    /**
     * @Serializer\SerializedName("IdCode")
     * @ORM\ManyToOne(targetEntity="App\Entity\InsuranceType", inversedBy="Contrats",cascade={"persist", "remove"})
     */
    private $type;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    }

    /**
     * @ORM\Column(type="date")
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private $dateSuppression;


    public function getId()
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getPolice(): ?string
    {
        return $this->police;
    }

    public function setPolice(string $police): self
    {
        $this->police = $police;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getIdSocietaire(): ?int
    {
        return $this->idSocietaire;
    }

    public function setIdSocietaire(int $idSocietaire): self
    {
        $this->idSocietaire = $idSocietaire;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(\DateTimeInterface $dateEcheance): self
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }



    public function getDateSuppression()
    {
        return $this->dateSuppression;
    }

    public function setDateSuppression($dateSuppression): self
    {
        $this->dateSuppression = $dateSuppression;

        return $this;
    }
}
