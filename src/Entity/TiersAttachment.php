<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TiersAttachmentRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class TiersAttachment
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose()
     * @Serializer\Groups("show_predeclaration")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_predeclaration"})
     */
    private $path;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_predeclaration"})
     * @Serializer\Type("DateTime<'d-m-Y H:i'>")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tiers", inversedBy="attachments")
     */
    private $tiers;

    /**
     * TiersAttachment constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTiers(): ?Tiers
    {
        return $this->tiers;
    }

    public function setTiers(?Tiers $tiers): self
    {
        $this->tiers = $tiers;

        return $this;
    }

}
