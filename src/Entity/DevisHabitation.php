<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\DevisHabitationRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\VirtualProperty(name="societaire_code", exp="object.getSocietaireCode()", options={ @Serializer\SerializedName("Type"), @Serializer\Groups("request_mrh") })
 * @Serializer\VirtualProperty(name="categorie_code", exp="object.getCategorieCode()", options={ @Serializer\SerializedName("Catgorie"), @Serializer\Groups("request_mrh") })
 * @Serializer\VirtualProperty(name="propriete_code", exp="object.getProprieteCode()", options={ @Serializer\SerializedName("Propriete"), @Serializer\Groups("request_mrh") })
 */
class DevisHabitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_accepted", "devis_refused"})
     */
    private $id;

    /**
     *
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $civilite;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_auto"})
     * @Assert\Regex(pattern="/^0[0-9]{9}$/", groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $tel;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     * @Assert\Email(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @Serializer\SerializedName("ValeurContenu")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh", "request_mrh" })
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $contenu;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh"})
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MrhCategorie")
     */
    private $categorie;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh"})
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MrhPropriete")
     */
    private $propriete;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh"})
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Societaire")
     */
    private $societaire;


    /**
     * @Serializer\SerializedName("ValeurBatiment")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh", "request_mrh" })
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $batiment;

    /**
     * @Serializer\SerializedName("SituationRisque")
     * @Serializer\Groups(groups={"devis_mrh", "request_mrh" })
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $situationRisque;


    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $createdAt;

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return DevisHabitation
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     * @return DevisHabitation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     * @return DevisHabitation
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * @param mixed $civilite
     * @return DevisHabitation
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;
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
     * @return DevisHabitation
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return DevisHabitation
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @param mixed $contenu
     * @return DevisHabitation
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getCategorie(): ?MrhCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(?MrhCategorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPropriete(): ?MrhPropriete
    {
        return $this->propriete;
    }

    public function setPropriete(?MrhPropriete $propriete): self
    {
        $this->propriete = $propriete;

        return $this;
    }

    public function getSocietaire(): ?Societaire
    {
        return $this->societaire;
    }

    public function setSocietaire(?Societaire $societaire): self
    {
        $this->societaire = $societaire;

        return $this;
    }

    public function getBatiment(): ?float
    {
        return $this->batiment;
    }

    public function setBatiment(float $batiment): self
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getSituationRisque(): ?string
    {
        return $this->situationRisque;
    }

    public function setSituationRisque(string $situationRisque): self
    {
        $this->situationRisque = $situationRisque;

        return $this;
    }

    public function getSocietaireCode()
    {
        return $this->getSocietaire()->getCode();
    }

    public function getCategorieCode()
    {
        return $this->getCategorie()->getCode();
    }

    public function getProprieteCode()
    {
        return $this->getPropriete()->getCode();
    }


}
