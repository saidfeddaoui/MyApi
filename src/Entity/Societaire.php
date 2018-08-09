<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\SocietaireRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Societaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("societaire")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"societaire","devis_auto","request_auto", "devis_mrh"})
     *
     * @Assert\NotBlank(groups={"devis_auto"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CodeInsurance;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCodeInsurance(): ?string
    {
        return $this->CodeInsurance;
    }

    public function setCodeInsurance(string $CodeInsurance): self
    {
        $this->CodeInsurance = $CodeInsurance;

        return $this;
    }
}
