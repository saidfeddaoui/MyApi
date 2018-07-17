<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Contract
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"scenarios","client_pre_declaration"})
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $contract_number;
    /**
     * @var PreDeclaration
     * @ORM\OneToMany(targetEntity="App\Entity\PreDeclaration", mappedBy="contract")
     */
    private $preDeclarations;
    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="contracts")
     */
    private $client;

    public function __construct()
    {
        $this->preDeclarations = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
    /**
     * @return null|string
     */
    public function getContractNumber(): ?string
    {
        return $this->contract_number;
    }
    /**
     * @param string $contract_number
     * @return Contract
     */
    public function setContractNumber(string $contract_number): self
    {
        $this->contract_number = $contract_number;
        return $this;
    }
    /**
     * @return Collection|PreDeclaration[]
     */
    public function getPreDeclarations(): Collection
    {
        return $this->preDeclarations;
    }
    /**
     * @param PreDeclaration $preDeclaration
     * @return Contract
     */
    public function addPreDeclaration(PreDeclaration $preDeclaration): self
    {
        if (!$this->preDeclarations->contains($preDeclaration)) {
            $this->preDeclarations[] = $preDeclaration;
            $preDeclaration->setContract($this);
        }
        return $this;
    }
    /**
     * @param PreDeclaration $preDeclaration
     * @return Contract
     */
    public function removePreDeclaration(PreDeclaration $preDeclaration): self
    {
        if ($this->preDeclarations->contains($preDeclaration)) {
            $this->preDeclarations->removeElement($preDeclaration);
            // set the owning side to null (unless already changed)
            if ($preDeclaration->getContract() === $this) {
                $preDeclaration->setContract(null);
            }
        }
        return $this;
    }
    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }
    /**
     * @param Client|null $client
     * @return static
     */
    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

}
