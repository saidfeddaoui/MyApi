<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
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
    private $device_uid;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $version_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $version_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firebase_token;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $fuseau_horaire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pushable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resolution;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $density;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Client", inversedBy="device")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $os;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceUid(): ?string
    {
        return $this->device_uid;
    }

    public function setDeviceUid(string $device_uid): self
    {
        $this->device_uid = $device_uid;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getVersionName(): ?string
    {
        return $this->version_name;
    }

    public function setVersionName(string $version_name): self
    {
        $this->version_name = $version_name;

        return $this;
    }

    public function getVersionCode(): ?string
    {
        return $this->version_code;
    }

    public function setVersionCode(?string $version_code): self
    {
        $this->version_code = $version_code;

        return $this;
    }

    public function getFirebaseToken(): ?string
    {
        return $this->firebase_token;
    }

    public function setFirebaseToken(string $firebase_token): self
    {
        $this->firebase_token = $firebase_token;

        return $this;
    }

    public function getFuseauHoraire(): ?string
    {
        return $this->fuseau_horaire;
    }

    public function setFuseauHoraire(?string $fuseau_horaire): self
    {
        $this->fuseau_horaire = $fuseau_horaire;

        return $this;
    }

    public function getPushable(): ?bool
    {
        return $this->pushable;
    }

    public function setPushable(bool $pushable): self
    {
        $this->pushable = $pushable;

        return $this;
    }

    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    public function setResolution(?string $resolution): self
    {
        $this->resolution = $resolution;

        return $this;
    }

    public function getDensity(): ?string
    {
        return $this->density;
    }

    public function setDensity(string $density): self
    {
        $this->density = $density;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

        return $this;
    }


    public function getOs(): ?string
    {
        return $this->os;
    }

    public function setOs(?string $os): self
    {
        $this->os = $os;

        return $this;
    }

    public function __toString()
    {
        return $this->device_uid;
    }


}
