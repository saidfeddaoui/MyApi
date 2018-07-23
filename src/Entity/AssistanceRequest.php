<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssistanceRequestRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class AssistanceRequest
{

    const STATUS_IN_PROGRESS = 0;
    const STATUS_HANDLED = 1;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"new_assistance"})
     *
     * @Assert\Valid(groups={"new_assistance"})
     * @Assert\NotNull(groups={"new_assistance"})
     *
     * @ORM\OneToOne(targetEntity="App\Entity\PersonalInformation", inversedBy="assistanceRequest", cascade={"persist", "remove"})
     */
    private $personalInformation;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"new_assistance"})
     *
     * @Assert\Regex(pattern="/^\-?\d+(\.\d+)?$/", groups={"new_assistance"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"new_assistance"})
     *
     * @Assert\Regex(pattern="/^\-?\d+(\.\d+)?$/", groups={"new_assistance"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InsuranceType")
     */
    private $insuranceType;
    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $status;
    /**
     * @var \DateTime
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
     * @return PersonalInformation|null
     */
    public function getPersonalInformation(): ?PersonalInformation
    {
        return $this->personalInformation;
    }
    /**
     * @param PersonalInformation|null $personalInformation
     * @return AssistanceRequest
     */
    public function setPersonalInformation(?PersonalInformation $personalInformation): self
    {
        $this->personalInformation = $personalInformation;
        return $this;
    }
    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }
    /**
     * @param float|null $latitude
     * @return AssistanceRequest
     */
    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }
    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
    /**
     * @param float|null $longitude
     * @return AssistanceRequest
     */
    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

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
     * @return AssistanceRequest
     */
    public function setInsuranceType(?InsuranceType $insuranceType): self
    {
        $this->insuranceType = $insuranceType;
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
