<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonalInformationRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class PersonalInformation
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"{"new_assistance"}"})
     *
     * @Assert\Choice(choices={"Mr", "Mme", "Mlle"}, groups={"new_assistance"})
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $civilite;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"{"new_assistance"}"})
     *
     * @Assert\NotBlank(groups={"new_assistance"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"{"new_assistance"}"})
     *
     * @Assert\NotBlank(groups={"new_assistance"})
     * @Assert\Regex(pattern="/^0[0-9]{9}$/", groups={"new_assistance"})
     *
     * @ORM\Column(type="string", length=20)
     */
    private $phone;
    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"{"new_assistance"}"})
     *
     * @Assert\NotBlank(groups={"new_assistance"})
     * @Assert\Email(groups={"new_assistance"})
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $email;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AssistanceRequest", mappedBy="personalInformation", cascade={"persist", "remove"})
     */
    private $assistanceRequest;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getCivilite(): ?string
    {
        return $this->civilite;
    }
    /**
     * @param null|string $civilite
     * @return static
     */
    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;
        return $this;
    }
    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    /**
     * @param string $phone
     * @return static
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    /**
     * @param null|string $email
     * @return static
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
    /**
     * @return AssistanceRequest|null
     */
    public function getAssistanceRequest(): ?AssistanceRequest
    {
        return $this->assistanceRequest;
    }
    /**
     * @param AssistanceRequest|null $assistanceRequest
     * @return static
     */
    public function setAssistanceRequest(?AssistanceRequest $assistanceRequest): self
    {
        $this->assistanceRequest = $assistanceRequest;
        // set (or unset) the owning side of the relation if necessary
        $newPersonalInformation = $assistanceRequest === null ? null : $this;
        if ($newPersonalInformation !== $assistanceRequest->getPersonalInformation()) {
            $assistanceRequest->setPersonalInformation($newPersonalInformation);
        }
        return $this;
    }

}
