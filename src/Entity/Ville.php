<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Translatable\Translatable as TranslatableInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Ville implements TranslatableInterface
{
    use Translatable;

    /**
     * @Serializer\Expose()
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InsuranceType", inversedBy="villes")
     */
    private $insuranceType;

    public function getId()
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
