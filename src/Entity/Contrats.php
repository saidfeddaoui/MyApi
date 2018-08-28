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
     *
     *  @SWG\Post(
     *     tags={"Contrat"},
     *     description="liste des contrats a suprimer",
     *     @SWG\Parameter(
     *        name="contrat",
     *        in="body",
     *        description="contrats object",
     *        required=true,
     *        @Model(type="App\Entity\Contrats", groups={"contratsForDelete"})
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Failure response",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Validation Error (Http Code: 406)":
     *             {
     *                 "code"=406,
     *                 "status"="Constraint Violation Error"
     *             },
     *             "Not Found Error (Http Code: 404)":
     *             {
     *                 "code"=404,
     *                 "status"="Resource Not Found"
     *             }
     *         }
     *     )
     * )
     *
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
     * @ORM\Column(type="date")
     * @Serializer\Expose()
     * @Serializer\Groups("contrats")
     * @Serializer\Type("DateTime<'Y-m-d'>")
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

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(\DateTimeInterface $dateEcheance): self
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }
}
