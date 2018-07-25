<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PackRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Pack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("pack")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"pack","devis_auto","request_auto"})
     *
     * @Assert\NotBlank(groups={"devis_auto"})
     * @ORM\Column(type="string", length=255, )
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Societaire")
     */
    private $societaire;

    /**
     * Pack constructor.
     * @param $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

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

    public function getSocietaire(): ?Societaire
    {
        return $this->societaire;
    }

    public function setSocietaire(?Societaire $societaire): self
    {
        $this->societaire = $societaire;

        return $this;
    }
}
