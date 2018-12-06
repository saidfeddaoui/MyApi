<?php

namespace App\Entity;

use App\Repository\TiersAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;
use Doctrine\Common\Collections\ArrayCollection;


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
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"listPreDeclaration","client_pre_declaration"})
     *
     */
    private $id;

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
     * @Serializer\Groups({"client_pre_declaration","listPreDeclaration"})
     *
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\DateTime(groups={"client_pre_declaration"}, format="Y-m-d H:i:s")
     *
     * @ORM\Column(type="datetime")
     */
    private $dateSinistre;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"show_predeclaration","listPreDeclaration"})
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
     * @Serializer\Groups({"client_pre_declaration","listPreDeclaration"})
     *
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Contrats", inversedBy="preDeclaration")
     */
    private $contrat;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     * @Assert\NotNull(groups={"client_pre_declaration"})
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\CirconstanceSinistre", inversedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $circonstanceSinistre;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration","listPreDeclaration"})
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
     * @ORM\OneToMany(targetEntity="App\Entity\TiersAttachment", mappedBy="preDeclaration", cascade={"persist", "remove"})
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


    /**
     * @var Client
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration","listPreDeclaration"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="preDeclaration")
     * @Type("App\Entity\Client")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="ChoixMDR", mappedBy="preDeclaration")
     */
    protected $preDeclaration_modeReparation_associations;

    /**
     * @var integer
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     * @Assert\GreaterThanOrEqual(0 , groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     */
    private $idVille;

    /**
     * @var integer
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     * @Assert\GreaterThanOrEqual(0 , groups={"client_pre_declaration"})
     * @ORM\Column(type="integer",nullable=true)
     */
    private $idGarage;


    /**
     * @var integer
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     * @Assert\GreaterThanOrEqual(0 , groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $idModeReparation;


    /**
     * @var integer
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     *
     * @ORM\Column(type="string",length=100, nullable=true)
     *
     */
    private $ville;

    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     * @ORM\Column(type="string",length=250, nullable=true)
     */
    private $garage;


    /**
     * @var integer
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     *
     * @ORM\Column(type="string",length=250, nullable=true)
     */
    private $modeReparation;


    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     *
     * @ORM\Column(type="string",length=10, nullable=true)
     */
    private $codeModeReparation;

    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $commentaire;


    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"listPreDeclaration"})
     *
     *
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isReparation = false;


    /**
     * @var \DateTime
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateTraitement;


    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255, nullable=true)
     */
     private $operateurTraitement;

    /**
     * @var boolean
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
     private $expertise = true;



    public function __construct()
    {
        $this->preDeclaration_modeReparation_associations = new ArrayCollection();
    }



    /**
     * Add preDeclaration_modeReparation_associations
     *
     * @param ChoixMDR  $preDeclaration_modeReparation_associations
     * @return PreDeclaration
     */
    public function addUserRecipeAssociation(ChoixMDR $preDeclaration_modeReparation_associations)
    {
        $this->preDeclaration_modeReparation_associations[] = $preDeclaration_modeReparation_associations;

        return $this;
    }

    /**
     * Remove preDeclaration_modeReparation_associations
     *
     * @param ChoixMDR $preDeclaration_modeReparation_associations
     */
    public function removeUserRecipeAssociation(ChoixMDR $preDeclaration_modeReparation_associations)
    {
        $this->preDeclaration_modeReparation_associations->removeElement($preDeclaration_modeReparation_associations);
    }

    /**
     * Get preDeclaration_modeReparation_associations
     *
     * @return ArrayCollection
     */
    public function getPreDeclaration_modeReparation_associations()
    {
        return $this->preDeclaration_modeReparation_associations;
    }


    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"listPreDeclaration"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference;

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client |null $client
     * @return static
     */
    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

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
     * @return CirconstanceSinistre|null
     */
    public function getCirconstanceSinistre(): ?CirconstanceSinistre
    {
        return $this->circonstanceSinistre;
    }
    /**
     * @param CirconstanceSinistre|null $circonstanceSinistre
     * @return static
     */
    public function setCirconstanceSinistre(?CirconstanceSinistre $circonstanceSinistre): self
    {
        $this->circonstanceSinistre = $circonstanceSinistre;
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
    public function setImages(?TiersAttachment $images)
    {
        $this->images = $images;
        return $images;
    }


    /**
     * @param mixed $attachments
     * @return TiersAttachment
     */
    public function setAttachments($attachments)
    {
        foreach ($this->images as $attachment) {
            $this->removeAttachment($attachment);
        }
        foreach ($attachments as $attachment){
            $this->addAttachment($attachment);
        }
        return $this;
    }

    public function addAttachment(TiersAttachment $attachment): self
    {
        if (!$this->images->contains($attachment)) {
            $this->images[] = $attachment;
            $attachment->setPreDeclaration($this);
        }

        return $this;
    }

    public function removeAttachment(TiersAttachment $attachment): self
    {
        if ($this->images->contains($attachment)) {
            $this->images->removeElement($attachment);
            // set the owning side to null (unless already changed)
            if ($attachment->getPreDeclaration() === $this) {
                $attachment->setPreDeclaration(null);
            }
        }

        return $this;
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


   /**
     * @return \DateTimeInterface|null
     */
    public function getDateTraitement(): ?\DateTimeInterface
    {
        return $this->dateTraitement;
    }
    /**
     * @param \DateTimeInterface $dateTraitement
     * @return static
     */
    public function setDateTraitement(\DateTimeInterface $dateTraitement): self
    {
        $this->dateTraitement = $dateTraitement;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdVille()
    {
        return $this->idVille;
    }

    /**
     * @param int $ville
     */
    public function setIdVille($idVille): void
    {
        $this->idVille = $idVille;
    }

    /**
     * @return int
     */
    public function getIdGarage()
    {
        return $this->idGarage;
    }

    /**
     * @param int $Garage
     */
    public function setIdGarage($idGarage): void
    {
        $this->idGarage = $idGarage;
    }

    /**
     * @return int
     */
    public function getIdModeReparation()
    {
        return $this->idModeReparation;
    }

    /**
     * @param int $idModeReparation
     */
    public function setIdModeReparation(string $idModeReparation): void
    {
        $this->idModeReparation = $idModeReparation;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille(string $ville): void
    {
        $this->ville = $ville;
    }

    /**
     * @return string
     */
    public function getGarage()
    {
        return $this->garage;
    }

    /**
     * @param string $Garage
     */
    public function setGarage(string $garage): void
    {
        $this->Garage = $garage;
    }

    /**
     * @return string
     */
    public function getModeReparation()
    {
        return $this->modeReparation;
    }

    /**
     * @param string $modeReparation
     */
    public function setModeReparation(string $modeReparation): void
    {
        $this->modeReparation = $modeReparation;
    }

    /**
     * @return string
     */
    public function getCodeModeReparation()
    {
        return $this->codeModeReparation;
    }

    /**
     * @param string $codeModeReparation
     */
    public function setCodeModeReparation(string $codeModeReparation): void
    {
        $this->codeModeReparation = $codeModeReparation;
    }

    /**
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     */
    public function setCommentaire(string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return boolean
     */
    public function getIsReparation()
    {
        return $this->isReparation;
    }

    /**
     * @param boolean $isReparation
     */
    public function setIsReparation($isReparation): void
    {
        $this->isReparation = $isReparation;
    }

    /**
     * @return boolean
     */
    public function getExpertise()
    {
        return $this->expertise;
    }

    /**
     * @param boolean $expertise
     */
    public function setExpertise($expertise): void
    {
        $this->expertise = $expertise;
    }

    /**
     * @return string
     */
    public function getOperateurTraitement()
    {
        return $this->operateurTraitement;
    }

    /**
     * @param string $operateurTraitement
     */
    public function setOperateurTraitement(string $operateurTraitement): void
    {
        $this->operateurTraitement = $operateurTraitement;
    }










}
