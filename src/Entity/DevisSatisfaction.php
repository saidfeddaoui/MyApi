<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DevisSatisfactionRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class DevisSatisfaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_accepted"})
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @Assert\NotNull(groups={"devis_accepted"})
     * @Assert\DateTime(groups={"devis_accepted"}, format="Y-m-d H:i:s")
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $echeanceDate;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_accepted"})
     *
     * @Assert\NotNull(groups={"devis_accepted"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agence;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_refused"})
     *
     * @Assert\NotNull(groups={"devis_refused"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raison;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_accepted", "devis_refused"})
     *
     * @Assert\NotNull(groups={"devis_accepted","devis_refused"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    public function getId()
    {
        return $this->id;
    }

    public function getEcheanceDate(): ?\DateTimeInterface
    {
        return $this->echeanceDate;
    }

    public function setEcheanceDate(\DateTimeInterface $echeanceDate): self
    {
        $this->echeanceDate = $echeanceDate;

        return $this;
    }

    public function getAgence(): ?string
    {
        return $this->agence;
    }

    public function setAgence(string $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}