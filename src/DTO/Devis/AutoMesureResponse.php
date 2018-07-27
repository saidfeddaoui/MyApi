<?php

namespace App\DTO\Devis;

use App\DTO\Api\Devis\ResultMesure;
use App\DTO\Api\Devis\Total;
use JMS\Serializer\Annotation as Serializer;

class AutoMesureResponse
{

    /**
     * @Serializer\SerializedName("result")
     * @Serializer\Type("App\DTO\Api\Devis\ResultMesure")
     * @Serializer\Groups("response_mesure")
     * @var ResultMesure
     */
    private $result;

    /**
     * @Serializer\SerializedName("total")
     * @Serializer\Type("App\DTO\Api\Devis\Total")
     * @Serializer\Groups("response_mesure")
     * @var Total
     */
    private $total;

    /**
     * @return ResultMesure
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param ResultMesure $result
     * @return AutoMesureResponse
     */
    public function setResult($result)
    {
        $this->result = $result;
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
     * @return AutoMesureResponse
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }


}