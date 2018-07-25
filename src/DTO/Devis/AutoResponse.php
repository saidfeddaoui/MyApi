<?php

namespace App\DTO\Devis;

use App\DTO\Api\Devis\Garantie;
use App\DTO\Api\Devis\Total;
use JMS\Serializer\Annotation as Serializer;

class AutoResponse
{

    /**
     * @Serializer\SerializedName("garanties")
     * @Serializer\Type("array<App\DTO\Api\Devis\Garantie>")
     * @Serializer\Groups("response_auto")
     * @var Garantie[]
     */
    private $garanties;

    /**
     * @Serializer\SerializedName("total")
     * @Serializer\Type("App\DTO\Api\Devis\Total")
     * @Serializer\Groups("response_auto")
     * @var Total
     */
    private $total;

    /**
     * @return Garantie[]
     */
    public function getGaranties()
    {
        return $this->garanties;
    }

    /**
     * @param Garantie[] $garanties
     * @return AutoResponse
     */
    public function setGaranties($garanties)
    {
        $this->garanties = $garanties;
        return $this;
    }

    /**
     * @return Total
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param Total $total
     * @return AutoResponse
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
}