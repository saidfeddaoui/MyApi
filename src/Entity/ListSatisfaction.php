<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Translatable\Translatable as TranslatableInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ListSatisfactionRepository")
 *  @Serializer\ExclusionPolicy("all")
 */
class ListSatisfaction implements TranslatableInterface
{

    use Translatable;

    /**
     * @Serializer\Expose()
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_list"})
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups(groups={"devis_list"})
     */
    private $title;


    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

}