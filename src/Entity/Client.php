<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Client extends User
{

    const STATUS_UNVERIFIED_WITH_SMS = 0;
    const STATUS_VERIFIED_WITH_SMS = 1;
    const STATUS_UNCONFIRMED_ACCOUNT = 2;
    const STATUS_CONFIRMED_ACCOUNT = 3;

    const CONTACT_SMS = 0;
    const CONTACT_PHONE = 1;

    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_account_creation"})
     *
     * @Assert\NotBlank(groups={"client_account_creation"})
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $name;
    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"phone_registration"})
     *
     * @Assert\NotBlank(groups={"phone_registration"})
     * @Assert\Regex(pattern="/^0[0-9]{9}$/", groups={"phone_registration"})
     *
     * @ORM\Column(type="string", length=20, unique=true)
     */
    protected $phone;
    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"phone_registration"})
     * @Assert\NotBlank(groups={"phone_registration"})
     * @Assert\Choice(choices={"ar", "fr"}, groups={"phone_registration"})
     *
     * @ORM\Column(type="string", length=4)
     */
    protected $lang;
    /**
     * @var int
     * @Serializer\Expose()
     * @Serializer\Groups({"client_account_creation"})
     *
     * @Assert\NotBlank(groups={"client_account_creation"})
     * @Assert\Choice(choices={0, 1}, groups={"client_account_creation"})
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $contactPreference;
    /**
     * @var string
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $verificationCode;
    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    protected $status;

    /**
     * @return string
     */
    public function getName(): string
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
     * @return string
     */
    public function getPhone(): string
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
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }
    /**
     * @param string $lang
     * @return static
     */
    public function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }
    /**
     * @return int
     */
    public function getContactPreference(): int
    {
        return $this->contactPreference;
    }
    /**
     * @param int $contactPreference
     * @return static
     */
    public function setContactPreference(int $contactPreference): self
    {
        $this->contactPreference = $contactPreference;
        return $this;
    }
    /**
     * @return string
     */
    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }
    /**
     * @param string $verificationCode
     * @return static
     */
    public function setVerificationCode(string $verificationCode): self
    {
        $this->verificationCode = $verificationCode;
        return $this;
    }
    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
    /**
     * @param int $status
     * @return static
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

}