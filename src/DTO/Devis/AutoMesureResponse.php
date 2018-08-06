<?php

namespace App\DTO\Devis;

use App\DTO\Api\Devis\Result;
use App\DTO\Api\Devis\Total;
use JMS\Serializer\Annotation as Serializer;

class AutoMesureResponse
{

    /**
     * @Serializer\SerializedName("result")
     * @Serializer\Type("App\DTO\Api\Devis\Result")
     * @Serializer\Groups("response_mesure")
     * @var Result
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
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param Result $result
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