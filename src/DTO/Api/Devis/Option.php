<?php

namespace App\DTO\Api\Devis;

use JMS\Serializer\Annotation as Serializer;

class Option
{

    /**
     * @Serializer\SerializedName("IdOption")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var string
     */
    private $id_option;

    /**
     * @Serializer\SerializedName("LibOption")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var string
     */
    private $lib_option;

    /**
     * @return string
     */
    public function getIdOption()
    {
        return $this->id_option;
    }

    /**
     * @param string $id_option
     * @return Option
     */
    public function setIdOption($id_option)
    {
        $this->id_option = $id_option;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibOption()
    {
        return $this->lib_option;
    }

    /**
     * @param string $lib_option
     * @return Option
     */
    public function setLibOption($lib_option)
    {
        $this->lib_option = $lib_option;
        return $this;
    }





}