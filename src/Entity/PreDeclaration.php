<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreDeclarationRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class PreDeclaration
{

    const STATUS_IN_PROGRESS = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;

    const TYPE_ACCIDENT = 'Accident';
    const TYPE_BRIS_GLACE = 'Bris de glace';
    const TYPE_INCENDIE = 'Incendie';
    const TYPE_VOL = 'Vol';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     * @Assert\GreaterThan(0 , groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbVehicule;
    /**
     * @var integer
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     * @Assert\GreaterThanOrEqual(0 , groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbInjured;
    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\DateTime(groups={"client_pre_declaration"}, format="Y-m-d H:i:s")
     *
     * @ORM\Column(type="datetime")
     */
    private $dateSinistre;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups("show_predeclaration")
     *
     * @ORM\Column(type="smallint")
     */
    private $status;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Contrats", inversedBy="preDeclaration")
     */
    private $contrat;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Item")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeSinistre;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Circumstance", inversedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $circumstance;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\OneToOne(targetEntity="App\Entity\VehiculeDamage", inversedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $vehiculeDamage;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TiersAttachement", mappedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $images;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InsuranceType",cascade={"persist", "remove"})
     */
    private $insuranceType;
    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId()
    {
        return $this->id;
    }
    /**
     * @return int|null
     */
    public function getNbVehicule(): ?int
    {
        return $this->nbVehicule;
    }
    /**
     * @param int $nbVehicule
     * @return static
     */
    public function setNbVehicule(int $nbVehicule): self
    {
        $this->nbVehicule = $nbVehicule;
        return $this;
    }
    /**
     * @return int|null
     */
    public function getNbInjured(): ?int
    {
        return $this->nbInjured;
    }
    /**
     * @param int|null $nbInjured
     * @return static
     */
    public function setNbInjured(?int $nbInjured): self
    {
        $this->nbInjured = $nbInjured;
        return $this;
    }
    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }
    /**
     * @param int $status
     * @return static
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }
    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    /**
     * @param null|string $description
     * @return static
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
    /**
     * @return Contrats|null
     */
    public function getContrat(): ?Contrats
    {
        return $this->contrat;
    }
    /**
     * @param Contrats|null $contrat
     * @return static
     */
    public function setContrat(?Contrats $contrat): self
    {
        $this->contrat = $contrat;
        return $this;
    }
    /**
     * @return Item|null
     */
    public function getTypeSinistre(): ?Item
    {
        return $this->typeSinistre;
    }
    /**
     * @param Item|null $typeSinistre
     * @return static
     */
    public function setTypeSinistre(?Item $typeSinistre): self
    {
        $this->typeSinistre = $typeSinistre;
        return $this;
    }
    /**
     * @return Circumstance|null
     */
    public function getCircumstance(): ?Circumstance
    {
        return $this->circumstance;
    }
    /**
     * @param Circumstance|null $circumstance
     * @return static
     */
    public function setCircumstance(?Circumstance $circumstance): self
    {
        $this->circumstance = $circumstance;
        return $this;
    }
    /**
     * @return VehiculeDamage|null
     */
    public function getVehiculeDamage(): ?VehiculeDamage
    {
        return $this->vehiculeDamage;
    }
    /**
     * @param VehiculeDamage|null $vehiculeDamage
     * @return static
     */
    public function setVehiculeDamage(?VehiculeDamage $vehiculeDamage): self
    {
        $this->vehiculeDamage = $vehiculeDamage;
        return $this;
    }
    /**
     * @return TiersAttachment|null
     */
    public function getImages()
    {
        return $this->images;
    }
    /**
     * @param TiersAttachment|null $images
     * @return static
     */
    public function setImages(?Tiers $images)
    {
        $this->images = $images;
        return $images;
    }
    /**
     * @return \DateTimeInterface|null
     */
    public function getDateSinistre(): ?\DateTimeInterface
    {
        return $this->dateSinistre;
    }
    /**
     * @param \DateTimeInterface $dateSinistre
     * @return static
     */
    public function setDateSinistre(\DateTimeInterface $dateSinistre): self
    {
        $this->dateSinistre = $dateSinistre;
        return $this;
    }
    /**
     * @return InsuranceType|null
     */
    public function getInsuranceType(): ?InsuranceType
    {
        return $this->insuranceType;
    }
    /**
     * @param InsuranceType|null $insuranceType
     * @return static
     */
    public function setInsuranceType(?InsuranceType $insuranceType): self
    {
        $this->insuranceType = $insuranceType;
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

}
