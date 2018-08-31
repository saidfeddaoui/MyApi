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

    const TYPE_DRIVER_LICENSE = 'permis';
    const TYPE_INSURANCE_ATTESTATION = 'attestation_assurance';
    const TYPE_FRIENDLY_REPORT = 'constat_amiable';
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
     * @var string
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose()
     * @Serializer\Groups("show_predeclaration")
     */
    private $type;

    /**
     * @var string
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
     * @ORM\ManyToOne(targetEntity="App\Entity\PreDeclaration", inversedBy="attachments")
     */
    private $preDeclaration;

    /**
     * TiersAttachment constructor.
     * @param string $type
     * @param string $path
     */
    public function __construct(string $type = null, string $path = null)
    {
        $this->type = $type;
        $this->path = $path;
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

    public function getPreDeclaration()
    {
        return $this->preDeclaration;
    }

    public function setTiers(PreDeclaration $preDeclaration)
    {
        $this->preDeclaration = $preDeclaration;
        return $this;
    }

    /**
     * @return array
     */
    public static function getAttachmentTypes()
    {
        return [
            self::TYPE_DRIVER_LICENSE,
            self::TYPE_INSURANCE_ATTESTATION,
            self::TYPE_FRIENDLY_REPORT,
        ];
    }
    /**
     * @return array
     */
    public static function getRequiredAttachmentTypes()
    {
        return [
            self::TYPE_DRIVER_LICENSE,
            self::TYPE_INSURANCE_ATTESTATION,
        ];
    }

}
