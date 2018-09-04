<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
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
     * @ORM\Column(type="string", length=255)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idSocietaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sujet;

    /**
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
     * @ORM\OneToMany(targetEntity="App\AppBundle\Entity\NotificationDetail", mappedBy="notification", cascade={"persist","remove"})
     */
    private $notificationDetails;



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
     * @param \App\AppBundle\Entity\NotificationDetail $notificationDetail
     *
     * @return Notification
     */
    public function addNotifcationDetail(\App\AppBundle\Entity\News $notificationDetail)
    {
        $this->notificationDetails[] = $notificationDetail;

        return $this;
    }

    /**
     * Remove notificationDetail
     *
     * @param \App\AppBundle\Entity\NotificationDetail $notificationDetail
     */
    public function removeNotificationDetail(\App\AppBundle\Entity\News $notificationDetail)
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
