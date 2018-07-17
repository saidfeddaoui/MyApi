<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Contract", inversedBy="preDeclarations")
     */
    private $contract;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Item")
     */
    private $scenario;

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
     * @ORM\OneToOne(targetEntity="App\Entity\Tiers", inversedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $tiers;

    public function getId()
    {
        return $this->id;
    }

    public function getNbVehicule(): ?int
    {
        return $this->nbVehicule;
    }

    public function setNbVehicule(int $nbVehicule): self
    {
        $this->nbVehicule = $nbVehicule;

        return $this;
    }

    public function getNbInjured(): ?int
    {
        return $this->nbInjured;
    }

    public function setNbInjured(?int $nbInjured): self
    {
        $this->nbInjured = $nbInjured;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getTypeSinistre(): ?Item
    {
        return $this->typeSinistre;
    }

    public function setTypeSinistre(?Item $typeSinistre): self
    {
        $this->typeSinistre = $typeSinistre;

        return $this;
    }

    public function getScenario(): ?Item
    {
        return $this->scenario;
    }

    public function setScenario(?Item $scenario): self
    {
        $this->scenario = $scenario;

        return $this;
    }

    public function getCircumstance(): ?Circumstance
    {
        return $this->circumstance;
    }

    public function setCircumstance(?Circumstance $circumstance): self
    {
        $this->circumstance = $circumstance;

        return $this;
    }

    public function getVehiculeDamage(): ?VehiculeDamage
    {
        return $this->vehiculeDamage;
    }

    public function setVehiculeDamage(?VehiculeDamage $vehiculeDamage): self
    {
        $this->vehiculeDamage = $vehiculeDamage;

        return $this;
    }

    public function getTiers(): ?Tiers
    {
        return $this->tiers;
    }

    public function setTiers(?Tiers $tiers): self
    {
        $this->tiers = $tiers;

        return $this;
    }

    public function getDateSinistre(): ?\DateTimeInterface
    {
        return $this->dateSinistre;
    }

    public function setDateSinistre(\DateTimeInterface $dateSinistre): self
    {
        $this->dateSinistre = $dateSinistre;

        return $this;
    }

}
