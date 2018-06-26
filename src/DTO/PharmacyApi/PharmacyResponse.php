<?php

namespace App\DTO\PharmacyApi;

use JMS\Serializer\Annotation as Serializer;

class PharmacyResponse
{

    /**
     * @Serializer\Type("array<\App\DTO\PharmacyApi\Pharmacy>")
     * @var Pharmacy[]
     */
    private $pharmacies;

    /**
     * @return Pharmacy
     */
    public function getPharmacies(): Pharmacy
    {
        return $this->pharmacies;
    }
    /**
     * @param Pharmacy $pharmacies
     * @return static
     */
    public function setPharmacies(Pharmacy $pharmacies): self
    {
        $this->pharmacies = $pharmacies;
        return $this;
    }

    public function getNearestPharmacy()
    {
        return $this->pharmacies[0];
    }
}