<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TiersRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Tiers
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     * @Assert\NotBlank(groups={"client_pre_declaration"})
     * @Assert\Regex(pattern="/^[a-zA-Z0-9]+$/", groups={"client_pre_declaration"})
     *
     * @ORM\Column(type="string", length=20)
     */
    private $immatriculation;

    /**
     *
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_pre_declaration"})
     * @Assert\Valid(groups={"client_pre_declaration"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TiersAttachment", mappedBy="tiers", cascade={"persist"})
     */
    private $attachments;

    /**
     * @ORM\OneToOne(targetEntity="PreDeclaration", mappedBy="tiers", cascade={"persist", "remove"})
     */
    private $preDeclaration;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    /**
     * @return Collection|TiersAttachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    /**
     * @param mixed $attachments
     * @return Tiers
     */
    public function setAttachments($attachments)
    {
        foreach ($this->attachments as $attachment) {
            $this->removeAttachment($attachment);
        }
        foreach ($attachments as $attachment){
            $this->addAttachment($attachment);
        }
        return $this;
    }

    public function addAttachment(TiersAttachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setTiers($this);
        }

        return $this;
    }

    public function removeAttachment(TiersAttachment $attachment): self
    {
        if ($this->attachments->contains($attachment)) {
            $this->attachments->removeElement($attachment);
            // set the owning side to null (unless already changed)
            if ($attachment->getTiers() === $this) {
                $attachment->setTiers(null);
            }
        }

        return $this;
    }

    public function getPreDeclaration(): ?PreDeclaration
    {
        return $this->preDeclaration;
    }

    public function setPreDeclaration(?PreDeclaration $preDeclaration): self
    {
        $this->preDeclaration = $preDeclaration;

        // set (or unset) the owning side of the relation if necessary
        $newTiers = $preDeclaration === null ? null : $this;
        if ($newTiers !== $preDeclaration->getTiers()) {
            $preDeclaration->setTiers($newTiers);
        }

        return $this;
    }

}
