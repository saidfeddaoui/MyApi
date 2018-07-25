<?php

namespace App\DTO\Api\Devis;

use JMS\Serializer\Annotation as Serializer;

class Total
{

    /**
     * @Serializer\SerializedName("Id")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var int
     */
    private $id;

    /**
     * @Serializer\SerializedName("PrimeHT")
     * @Serializer\Type("double")
     * @Serializer\Groups("response_auto")
     * @var double
     */
    private $primeht;

    /**
     * @Serializer\SerializedName("PrimeTTC")
     * @Serializer\Type("double")
     * @Serializer\Groups("response_auto")
     * @var double
     */
    private $primettc;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Total
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrimeht()
    {
        return $this->primeht;
    }

    /**
     * @param float $primeht
     * @return Total
     */
    public function setPrimeht($primeht)
    {
        $this->primeht = $primeht;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrimettc()
    {
        return $this->primettc;
    }

    /**
     * @param float $primettc
     * @return Total
     */
    public function setPrimettc($primettc)
    {
        $this->primettc = $primettc;
        return $this;
    }


}