<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MrhBatimentRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class MrhBatiment
{
    /**
     * @Serializer\Expose()
     * @Serializer\Groups("all")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose()
     * @Serializer\Groups("all")
     */
    private $value;

    public function getId()
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
