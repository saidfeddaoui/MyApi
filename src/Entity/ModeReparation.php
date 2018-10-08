<?php
/**
 * Created by PhpStorm.
 * User: mobiblanc
 * Date: 08/10/2018
 * Time: 11:03
 */

namespace App\Entity;


/**
 * @ORM\Entity()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ModeReparation
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"modeReparation"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"all"})
     */
    private $description;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }





}