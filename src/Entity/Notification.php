<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="Notification",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="IdCompteMobile", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idSocietaire;



    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PreDeclaration", inversedBy="Notification",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="idPredeclaration", referencedColumnName="id")
     */
    private $predeclaration;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_notification"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sujet;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_notification"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_notification"})
     * @ORM\OneToMany(targetEntity="App\Entity\NotificationDetail", mappedBy="notification", cascade={"persist","remove"})
     */
    private $notificationDetails;


    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_notification"})
     *
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $type;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notificationDetails = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add notificationDetail
     *
     * @param \App\Entity\NotificationDetail $notificationDetail
     *
     * @return Notification
     */
    public function addNotifcationDetail(\App\Entity\NotificationDetail $notificationDetail)
    {
        $this->notificationDetails[] = $notificationDetail;

        return $this;
    }

    /**
     * Remove notificationDetail
     *
     * @param \App\Entity\NotificationDetail $notificationDetail
     */
    public function removeNotificationDetail(\App\Entity\NotificationDetail  $notificationDetail)
    {
        $this->notificationDetails->removeElement($notificationDetail);
    }

    /**
     * Get notifcationDetail
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificationDetails()
    {
        return $this->notificationDetails;
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
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return PreDeclaration|null
     */
    public function getPredeclaration(): ?PreDeclaration
    {
        return $this->predeclaration;
    }

    /**
     * @param PreDeclaration |null $predeclaration
     * @return static
     */
    public function setPredeclaration(?PreDeclaration $predeclaration): self
    {
        $this->predeclaration = $predeclaration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdSocietaire()
    {
        return $this->idSocietaire;
    }

    /**
     * @param mixed $idSocietaire
     */
    public function setIdSocietaire($idSocietaire)
    {
        $this->idSocietaire = $idSocietaire;
    }

    /**
     * @return mixed
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * @param mixed $sujet
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    }



    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $sujet
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param mixed $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

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




}
