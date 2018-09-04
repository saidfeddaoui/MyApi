<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CircumstanceRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Circumstance
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var double
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotBlank(groups={"client_pre_declaration"})
     * @Assert\Regex(pattern="/^\-?\d+(\.\d+)?$/", groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var double
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     *
     * @Assert\NotBlank(groups={"client_pre_declaration"})
     * @Assert\Regex(pattern="/^\-?\d+(\.\d+)?$/", groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Groups({"client_pre_declaration"})
     *
     * @Assert\NotBlank(groups={"client_pre_declaration"})
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $adress;

    /**
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * @param string $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
    }


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PreDeclaration", mappedBy="circumstance", cascade={"persist", "remove"})
     */
    private $preDeclaration;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getPreDeclaration(): ?PreDeclaration
    {
        return $this->preDeclaration;
    }

    public function setPreDeclaration(?PreDeclaration $preDeclaration): self
    {
        $this->preDeclaration = $preDeclaration;

        // set (or unset) the owning side of the relation if necessary
        $newCircumstance = $preDeclaration === null ? null : $this;
        if ($newCircumstance !== $preDeclaration->getCircumstance()) {
            $preDeclaration->setCircumstance($newCircumstance);
        }

        return $this;
    }

}
