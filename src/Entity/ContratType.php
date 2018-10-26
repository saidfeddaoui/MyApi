<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContratTypeRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class ContratType
{
    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"entities"})
     * 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"entities"})
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"entities"})
     * 
     * @ORM\Column(type="string", length=10)
     */
    private $code;

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
}
