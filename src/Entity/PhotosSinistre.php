<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotosSinistreRepository")
 */
class PhotosSinistre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="photosSinistres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\insuranceType", inversedBy="photosSinistres")
     */
    private $insuranceType;

    public function getId()
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getType(): ?Item
    {
        return $this->type;
    }

    public function setType(?Item $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getInsuranceType(): ?insuranceType
    {
        return $this->insuranceType;
    }

    public function setInsuranceType(?insuranceType $insuranceType): self
    {
        $this->insuranceType = $insuranceType;

        return $this;
    }
}
