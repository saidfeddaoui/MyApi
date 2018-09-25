<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VersionRepository")
 */
class Version
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $current_version;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $min_version;

    /**
     * @ORM\Column(type="text")
     */
    private $msg_info;

    /**
     * @ORM\Column(type="text")
     */
    private $msg_bloquer;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cache;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="integer")
     */
    private $version_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $os;


    public function __construct()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }


    public function getId()
    {
        return $this->id;
    }

    public function getCurrentVersion(): ?string
    {
        return $this->current_version;
    }

    public function setCurrentVersion(string $current_version): self
    {
        $this->current_version = $current_version;

        return $this;
    }

    public function getMinVersion(): ?string
    {
        return $this->min_version;
    }

    public function setMinVersion(string $min_version): self
    {
        $this->min_version = $min_version;

        return $this;
    }

    public function getMsgInfo(): ?string
    {
        return $this->msg_info;
    }

    public function setMsgInfo(string $msg_info): self
    {
        $this->msg_info = $msg_info;

        return $this;
    }

    public function getMsgBloquer(): ?string
    {
        return $this->msg_bloquer;
    }

    public function setMsgBloquer(string $msg_bloquer): self
    {
        $this->msg_bloquer = $msg_bloquer;

        return $this;
    }

    public function getCache(): ?bool
    {
        return $this->cache;
    }

    public function setCache(bool $cache): self
    {
        $this->cache = $cache;

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


    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getVersionCode(): ?int
    {
        return $this->version_code;
    }

    public function setVersionCode(int $version_code): self
    {
        $this->version_code = $version_code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param mixed $os
     */
    public function setOs($os)
    {
        $this->os = $os;
    }



}
