<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DevisSatisfactionRepository")
 * @Serializer\ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks
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
     * @Serializer\Groups(groups={"devis_refused","devis_accepted"})
     * @Assert\NotNull(groups={"devis_refused"})
     * @ORM\JoinColumn(name="raison", referencedColumnName="id", nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\ListSatisfaction", inversedBy="DevisSatisfaction", cascade={"persist", "remove"})
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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;


    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_accepted", "devis_refused"})
     *
     */
    private $auto;



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

    public function getRaison(): ?ListSatisfaction
    {
        return $this->raison;
    }

    public function setRaison(?ListSatisfaction $raison): self
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

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param mixed $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }


    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        if ($this->getDateCreation() == null) {
            $this->setDateCreation(new \DateTime('now'));
        }
    }

}
