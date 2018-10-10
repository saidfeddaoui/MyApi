<?php
/**
 * Created by PhpStorm.
 * User: mobiblanc
 * Date: 08/10/2018
 * Time: 11:03
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(type="string", length=5)
     * @Serializer\Expose()
     * @Serializer\Groups({"all"})
     */
    private $code;


    /**
     * @ORM\OneToMany(targetEntity="ChoixMDR", mappedBy="modeReparation")
     */
    protected $modeReparation_associations;



    public function __construct()
    {
        $this->modeReparation_associations = new ArrayCollection();
    }



    /**
     * Add modeReparation_associations
     *
     * @param ChoixMDR  $modeReparation_associations
     * @return ModeReparation
     */
    public function addUserRecipeAssociation(ChoixMDR $modeReparation_associations)
    {
        $this->modeReparation_associations[] = $modeReparation_associations;

        return $this;
    }

    /**
     * Remove modeReparation_associations
     *
     * @param ChoixMDR $modeReparation_associations
     */
    public function removeUserRecipeAssociation(ChoixMDR $modeReparation_associations)
    {
        $this->modeReparation_associations->removeElement($modeReparation_associations);
    }

    /**
     * Get modeReparation_associations
     *
     * @return ArrayCollection
     */
    public function getPreDeclaration_modeReparation_associations()
    {
        return $this->modeReparation_associations;
    }



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

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }






}