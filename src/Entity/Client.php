<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * @Serializer\Groups({"client_account_creation","login_response"})
     *
     * @Assert\NotBlank(groups={"client_account_creation"})
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $familyName;

    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"client_account_creation","login_response"})
     *
     * @Assert\NotBlank(groups={"client_account_creation"})
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $firstName;
    /**
     * @var string
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"phone_registration","login_response"})
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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Contract", mappedBy="client")
     */
    protected $contracts;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Groups({"client_account_creation"})
     *
     * @Assert\NotBlank(groups={"client_account_creation"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $cin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contrats", mappedBy="client")
     */
    private $contrats;


    public function __construct()
    {
        $this->contracts = new ArrayCollection();
        $this->contrats = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }
    /**
     * @param string $familyName
     * @return static
     */
    public function setFamilyName(?string $familyName): self
    {
        $this->familyName = $familyName;
        return $this;
    }

      public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    /**
     * @param string $firstName
     * @return static
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
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
    /**
     * @return Collection|Contract[]
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }
    /**
     * @param Contract $contract
     * @return Client
     */
    public function addContract(Contract $contract): self
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts[] = $contract;
            $contract->setClient($this);
        }

        return $this;
    }
    /**
     * @param Contract $contract
     * @return Client
     */
    public function removeContract(Contract $contract): self
    {
        if ($this->contracts->contains($contract)) {
            $this->contracts->removeElement($contract);
            // set the owning side to null (unless already changed)
            if ($contract->getClient() === $this) {
                $contract->setClient(null);
            }
        }

        return $this;
    }
    /**
     * @return null|string
     */
    public function getCin(): ?string
    {
        return $this->cin;
    }
    /**
     * @param string $cin
     * @return static
     */
    public function setCin(string $cin): self
    {
        $this->cin = $cin;
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Check if the client is unverified
     * @return bool
     */
    public function isUnverified()
    {
        return self::STATUS_UNVERIFIED_WITH_SMS === $this->getStatus();
    }
    /**
     * Check if the client is verified
     * @return bool
     */
    public function isVerified()
    {
        return self::STATUS_VERIFIED_WITH_SMS === $this->getStatus();
    }
    /**
     * Check if the client is unconfirmed
     * @return bool
     */
    public function isUnconfirmed()
    {
        return self::STATUS_UNCONFIRMED_ACCOUNT === $this->getStatus();
    }
    /**
     * Check if the client is confirmed
     * @return bool
     */
    public function isConfirmed()
    {
        return self::STATUS_CONFIRMED_ACCOUNT === $this->getStatus();
    }



    /**
     * @return Collection|Contrats[]
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrats(Contrats $contrats): self
    {
        if (!$this->contrats->contains($contrats)) {
            $this->contrats[] = $contrats;
            $contrats->setClient($this);
        }

        return $this;
    }

    public function removeContrats(Contrats $contrats): self
    {
        if ($this->contrats->contains($contrats)) {
            $this->contrats->removeElement($contrats);
            // set the owning side to null (unless already changed)
            if ($contrats->getClient() === $this) {
                $contrats->setClient(null);
            }
        }

        return $this;
    }

}
