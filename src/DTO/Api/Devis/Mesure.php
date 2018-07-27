<?php

namespace App\DTO\Api\Devis;

use JMS\Serializer\Annotation as Serializer;

class Mesure
{
    /**
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $id_devis;

    /**
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $id_garantie;
    /**
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $acquise;

    /**
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var string
     */
    private $id_option;

    /**
     * @return string
     */
    public function getIdDevis()
    {
        return $this->id_devis;
    }

    /**
     * @param string $id_devis
     * @return Mesure
     */
    public function setIdDevis($id_devis)
    {
        $this->id_devis = $id_devis;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdGarantie()
    {
        return $this->id_garantie;
    }

    /**
     * @param string $id_garantie
     * @return Mesure
     */
    public function setIdGarantie($id_garantie)
    {
        $this->id_garantie = $id_garantie;
        return $this;
    }

    /**
     * @return string
     */
    public function getAcquise()
    {
        return $this->acquise;
    }

    /**
     * @param string $acquise
     * @return Mesure
     */
    public function setAcquise($acquise)
    {
        $this->acquise = $acquise;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdOption()
    {
        return $this->id_option;
    }

    /**
     * @param string $id_option
     * @return Mesure
     */
    public function setIdOption($id_option)
    {
        $this->id_option = $id_option;
        return $this;
    }


}