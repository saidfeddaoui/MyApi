<?php
/**
 * Created by PhpStorm.
 * User: mobiblanc
 * Date: 08/10/2018
 * Time: 14:02
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ChoixMDR
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
     * @ORM\Column(type="boolean")
     * @Serializer\Expose()
     * @Serializer\Groups({"all"})
     */
    private $statut;


    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PreDeclaration", inversedBy="predeclaration__associations")
     * @ORM\JoinColumn(name="id_predeclaration", referencedColumnName="id")
     *
     */
    private $preDeclaration;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="client__associations")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     *
     */
    private $Client;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ModeReparation", inversedBy="mode_reparation__associations")
     * @ORM\JoinColumn(name="id_mode_reparation", referencedColumnName="id")
     *
     */
    private $modeReparation;


    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getPreDeclaration()
    {
        return $this->preDeclaration;
    }

    /**
     * @param mixed $preDeclaration
     */
    public function setPreDeclaration(PreDeclaration $preDeclaration)
    {
        $this->preDeclaration = $preDeclaration;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->Client;
    }

    /**
     * @param mixed $Client
     */
    public function setClient(Client $Client)
    {
        $this->Client = $Client;
    }

    /**
     * @return mixed
     */
    public function getModeReparation()
    {
        return $this->modeReparation;
    }

    /**
     * @param mixed $modeReparation
     */
    public function setModeReparation(ModeReparation $modeReparation)
    {
        $this->modeReparation = $modeReparation;
    }







}