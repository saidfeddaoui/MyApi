<?php

namespace App\DTO\Api\ContentType;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Pharmacy
 * @package App\DTO\Api\ContentType
 *
 * @Serializer\VirtualProperty(
 *     name="name",
 *     exp="object.getNom()"
 * )
 * @Serializer\VirtualProperty(
 *     name="address",
 *     exp="object.getAddresse()"
 * )
 * @Serializer\VirtualProperty(
 *     name="phone",
 *     exp="object.getTel()"
 * )
 */
class Pharmacy
{

    /**
     * @Serializer\Exclude(if="context.getDirection() === 1")
     * @Serializer\Type("string")
     * @var string
     */
    private $nom;
    /**
     * @Serializer\Exclude(if="context.getDirection() === 1")
     * @Serializer\Type("string")
     * @var string
     */
    private $addresse;
    /**
     * @Serializer\Exclude(if="context.getDirection() === 1")
     * @Serializer\Type("string")
     * @var string
     */
    private $tel;
    /**
     * @Serializer\Type("double")
     * @var double
     */
    private $latitude;
    /**
     * @Serializer\Type("double")
     * @var double
     */
    private $longitude;

    /**
     * Pharmacy constructor.
     * @param string $nom
     * @param string $addresse
     * @param string $tel
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(string $nom = null, string $addresse = null, string $tel = null, float $latitude = null, float $longitude = null)
    {
        $this->nom = $nom;
        $this->addresse = $addresse;
        $this->tel = $tel;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }
    /**
     * @param string $nom
     * @return static
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    /**
     * @return string
     */
    public function getAddresse(): string
    {
        return $this->addresse;
    }
    /**
     * @param string $addresse
     * @return static
     */
    public function setAddresse(string $addresse): self
    {
        $this->addresse = $addresse;
        return $this;
    }
    /**
     * @return string
     */
    public function getTel(): string
    {
        return $this->tel;
    }
    /**
     * @param string $tel
     * @return static
     */
    public function setTel(string $tel): self
    {
        $this->tel = $tel;
        return $this;
    }
    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }
    /**
     * @param float $latitude
     * @return static
     */
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }
    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }
    /**
     * @param float $longitude
     * @return static
     */
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

}