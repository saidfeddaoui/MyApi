<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DevisAutoRepository")
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\VirtualProperty(name="societaire_code", exp="object.getSocietaireCode()", options={ @Serializer\Groups("request_auto") })
 * @Serializer\VirtualProperty(name="pack_code", exp="object.getPackCode()", options={ @Serializer\Groups("request_auto") })
 */
class DevisAuto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @Serializer\Expose()
     * @Serializer\Groups("devis_auto")
     *
     * @Assert\NotNull(groups={"devis_auto"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_auto")
     *
     * @Assert\NotNull(groups={"devis_auto"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_auto")
     *
     * @Assert\NotNull(groups={"devis_auto"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $civilite;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_auto")
     *
     * @Assert\NotNull(groups={"devis_auto"})
     * @Assert\Regex(pattern="/^0[0-9]{9}$/", groups={"devis_auto"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $tel;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_auto")
     *
     * @Assert\NotNull(groups={"devis_auto"})
     * @Assert\Email(groups={"devis_auto"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_auto")
     *
     * @Assert\NotNull(groups={"devis_auto"})
     * @Assert\Valid(groups={"devis_auto"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MarqueVehicule")
     */
    private $marque;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto"})
     *
     * @Assert\NotNull(groups={"devis_auto"})
     * @Assert\Valid(groups={"devis_auto"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Societaire")
     * @ORM\JoinColumn(nullable=true)
     */
    private $societaire;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto"})
     *
     * @Assert\NotNull(groups={"devis_auto"})
     * @Assert\Valid(groups={"devis_auto"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Pack")
     * @ORM\JoinColumn(nullable=true)
     */
    private $pack;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto","request_auto"})
     *
     * @Assert\Choice(choices={"O", "N"}, groups={"devis_auto"})
     *
     * @ORM\Column(type="string", length=1, nullable = true)
     */
    private $w;

    /**
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto","request_auto"})
     *
     * @Assert\NotNull(groups={"devis_auto"})
     * @Assert\DateTime(groups={"devis_auto"}, format="Y-m-d H:i:s")
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $date_mec;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto","request_auto"})
     *
     * @Assert\NotNull(groups={"devis_auto"})
     *
     * @ORM\Column(type="integer", nullable = true)
     */
    private $puissance;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto","request_auto"})
     *
     * @Assert\NotNull(groups={"devis_auto"})
     *
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $combustion;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto","request_auto"})
     *
     * @Assert\NotNull(groups={"devis_auto"})
     *
     * @ORM\Column(type="float", nullable=true, nullable = true)
     */
    private $vn;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto","request_auto"})
     *
     * @Assert\NotNull(groups={"devis_auto"})
     *
     * @ORM\Column(type="float", nullable=true, nullable = true)
     */
    private $vv;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_auto","request_auto"})
     *
     * @Assert\NotNull(groups={"devis_auto"})
     *
     * @ORM\Column(type="float", nullable=true, nullable = true)
     */
    private $vg;

    public function getId()
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function getSocietaire(): ?Societaire
    {
        return $this->societaire;
    }

    public function setSocietaire(?Societaire $societaire): self
    {
        $this->societaire = $societaire;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;

        return $this;
    }

    public function getW(): ?string
    {
        return $this->w;
    }

    public function setW(string $w): self
    {
        $this->w = $w;

        return $this;
    }

    public function getDateMec(): ?\DateTimeInterface
    {
        return $this->date_mec;
    }

    public function setDateMec(\DateTimeInterface $date_mec): self
    {
        $this->date_mec = $date_mec;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): self
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getCombustion(): ?string
    {
        return $this->combustion;
    }

    public function setCombustion(string $combustion): self
    {
        $this->combustion = $combustion;

        return $this;
    }

    public function getVn(): ?float
    {
        return $this->vn;
    }

    public function setVn(?float $vn): self
    {
        $this->vn = $vn;

        return $this;
    }

    public function getVv(): ?float
    {
        return $this->vv;
    }

    public function setVv(?float $vv): self
    {
        $this->vv = $vv;

        return $this;
    }

    public function getVg(): ?float
    {
        return $this->vg;
    }

    public function setVg(?float $vg): self
    {
        $this->vg = $vg;

        return $this;
    }
    public function getSocietaireCode()
    {
        return $this->getSocietaire()->getCode();
    }
    public function getPackCode()
    {
        return $this->getPack()->getCode();
    }
}