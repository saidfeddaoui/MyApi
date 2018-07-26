<?php

namespace App\DTO\Api\Devis;

use JMS\Serializer\Annotation as Serializer;

class ResultMesure
{

    /**
     * @Serializer\SerializedName("succes")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $sucess;

    /**
     * @Serializer\SerializedName("IdDet")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $id_det;

    /**
     * @Serializer\SerializedName("Msg")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mesure")
     * @var string
     */
    private $msg;

    /**
     * @return string
     */
    public function getSucess()
    {
        return $this->sucess;
    }

    /**
     * @param string $sucess
     */
    public function setSucess($sucess)
    {
        $this->sucess = $sucess;
    }

    /**
     * @return string
     */
    public function getIdDet()
    {
        return $this->id_det;
    }

    /**
     * @param string $id_det
     */
    public function setIdDet($id_det)
    {
        $this->id_det = $id_det;
    }

    /**
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }


}