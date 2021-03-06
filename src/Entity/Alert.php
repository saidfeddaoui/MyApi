<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Type;
use Gedmo\Translatable\Translatable as TranslatableInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlertRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Alert
{
    use Translatable;



    public function __construct()
    {
        $this->checked = false;
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     * @Serializer\Expose()
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @Serializer\Expose()
     */
    private $subTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     * @Serializer\Expose()
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     *
     * @Serializer\Expose()
     */
    private $checked;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Attachment", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * @Serializer\Expose()
     */
    private $image;

    /**
     * @return mixed
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * @param mixed $checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    }

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose()
     * @Type("DateTime<'d-m-Y H:i'>")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_expiration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InsuranceType", inversedBy="alerts")
     */
    private $insuranceType;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(string $subTitle): self
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->date_expiration;
    }

    public function setDateExpiration(?\DateTimeInterface $date_expiration): self
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }

    public function getInsuranceType(): ?InsuranceType
    {
        return $this->insuranceType;
    }

    public function setInsuranceType(?InsuranceType $insuranceType): self
    {
        $this->insuranceType = $insuranceType;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;
        return $this;
    }

}
