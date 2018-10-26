<?php
/**
 * Created by PhpStorm.
 * User: Mobiblanc
 * Date: 26/10/2018
 * Time: 16:18
 */

namespace App\DTO\Api\ContentType;


class City
{

    /**
     * @Serializer\Exclude(if="context.getDirection() === 1")
     * @Serializer\Type("string")
     * @var string
     */
    private $nom;


    /**
     * Pharmacy constructor.
     * @param string $nom
     * @param string $addresse
     * @param string $tel
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(string $nom = null)
    {
        $this->nom = $nom;
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
}