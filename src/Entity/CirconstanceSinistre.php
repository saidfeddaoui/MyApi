<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Translatable\Translatable as TranslatableInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CirconstanceSinistreRepository")
 *  @Serializer\ExclusionPolicy("all")
 */
class CirconstanceSinistre implements TranslatableInterface
{

    use Translatable;

    /**
     * @Serializer\Expose()
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InsuranceType", inversedBy="circonstanceSinistres")
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

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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
}
