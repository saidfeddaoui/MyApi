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
     * @Serializer\Groups("contrats")
     */
    private $numeroContrat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="idSocietaire")
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
     * @return mixed
     */
    public function getDateEcheance()
    {
        return $this->dateEcheance;
    }

    /**
     * @param mixed $dateEcheance
     */
    public function setDateEcheance($dateEcheance)
    {
        $this->dateEcheance = $dateEcheance;
    }

    /**
     * @ORM\Column(type="date")
     * @Serializer\Expose()
     * @Serializer\Groups("contrats")
     */
    private $dateEcheance;



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

    public function getNumeroContrat(): ?string
    {
        return $this->numeroContrat;
    }

    public function setNumeroContrat(string $numeroContrat): self
    {
        $this->numeroContrat = $numeroContrat;

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

   /* public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(\DateTimeInterface $dateEcheance): self
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    } */
}
