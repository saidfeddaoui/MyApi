<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationDetailRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class NotificationDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_notification"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_notification"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $valeur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Notification", inversedBy="notificationDetails")
     * @ORM\JoinColumn(name="IdNotification", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notification;


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
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return mixed
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * @param mixed $valeur
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
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

    /**
     * Get tag
     *
     * @return \App\Entity\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set Notifcation
     *
     * @param \App\Entity\Notification $notification
     *
     * @return Notification
     */
    public function setNotification(\App\Entity\Notification $notification)
    {
        $this->notification = $notification;

        return $this;
    }




}
