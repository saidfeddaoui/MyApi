<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable as TranslatableInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Item implements TranslatableInterface
{
    use Translatable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"sinistre", "client_pre_declaration"})
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"slider", "products", "modes", "sinistre", "emergency","listPreDeclaration"})
     * @Gedmo\Translatable
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"emergency"})
     * @Assert\Regex(pattern="/^[0-9]+$/",message="numéro de téléphone n'est pas valide",groups={"emergency"});
     */
    private $subTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"slider", "products", "modes"})
     * @Gedmo\Translatable
     */
    private $content;

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"about", "slider"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"about", "slider"})
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"products"})
     * @Gedmo\Translatable
     */
    private $nosGaranties;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"products"})
     * @Gedmo\Translatable
     */
    private $nosPlus;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Attachment", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"slider", "products", "sinistre"})
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Attachment", cascade={"persist"})
     * @ORM\JoinColumn(name="icon_id", referencedColumnName="id")
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"products", "sinistre", "emergency"})
     */
    private $icon;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Serializer\Expose()
     */
    private $status = true;

    /**
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose()
     */
    private $position = 1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemList", inversedBy="items")
     *
     * @Serializer\Expose()
     */
    private $itemList;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhotosSinistre", mappedBy="type")
     */
    private $photosSinistres;

    public function __construct()
    {
        $this->photosSinistres = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(string $subTitle): self
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getItemList(): ?ItemList
    {
        return $this->itemList;
    }

    public function setItemList(?ItemList $itemList): self
    {
        $this->itemList = $itemList;

        return $this;
    }

    /**
     * @return Collection|PhotosSinistre[]
     */
    public function getPhotosSinistres(): Collection
    {
        return $this->photosSinistres;
    }

    public function addPhotosSinistre(PhotosSinistre $photosSinistre): self
    {
        if (!$this->photosSinistres->contains($photosSinistre)) {
            $this->photosSinistres[] = $photosSinistre;
            $photosSinistre->setType($this);
        }

        return $this;
    }

    public function removePhotosSinistre(PhotosSinistre $photosSinistre): self
    {
        if ($this->photosSinistres->contains($photosSinistre)) {
            $this->photosSinistres->removeElement($photosSinistre);
            // set the owning side to null (unless already changed)
            if ($photosSinistre->getType() === $this) {
                $photosSinistre->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNosGaranties()
    {
        return $this->nosGaranties;
    }

    /**
     * @param mixed $nosGaranties
     * @return Item
     */
    public function setNosGaranties($nosGaranties)
    {
        $this->nosGaranties = $nosGaranties;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNosPlus()
    {
        return $this->nosPlus;
    }

    /**
     * @param mixed $nosPlus
     * @return Item
     */
    public function setNosPlus($nosPlus)
    {
        $this->nosPlus = $nosPlus;
        return $this;
    }



}
