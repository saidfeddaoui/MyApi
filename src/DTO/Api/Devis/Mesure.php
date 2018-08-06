<?php

namespace App\DTO\Api\Devis;

use JMS\Serializer\Annotation as Serializer;

class Mesure
{
    /**
     * @Serializer\SerializedName("Id")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $id_devis;

    /**
     * @Serializer\SerializedName("IdGarantie")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $id_garantie;
    /**
     * @Serializer\SerializedName("Acquise")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $acquise;

    /**
     * @Serializer\SerializedName("IdDet")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $id_det;

    /**
     * @Serializer\SerializedName("IdOption")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $id_option;

    /**
     * @Serializer\SerializedName("ValeurAssuree")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $valeurAssuree;

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

    /**
     * @return mixed
     */
    public function getIdDet()
    {
        return $this->id_det;
    }

    /**
     * @param mixed $id_det
     * @return Mesure
     */
    public function setIdDet($id_det)
    {
        $this->id_det = $id_det;
        return $this;
    }

    /**
     * @return string
     */
    public function getValeurAssuree()
    {
        return $this->valeurAssuree;
    }

    /**
     * @param string $valeurAssuree
     * @return Mesure
     */
    public function setValeurAssuree($valeurAssuree)
    {
        $this->valeurAssuree = $valeurAssuree;
        return $this;
    }



}