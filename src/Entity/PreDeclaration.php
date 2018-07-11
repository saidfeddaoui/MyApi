<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreDeclarationRepository")
 */
class PreDeclaration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_vehicule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contract", inversedBy="preDeclarations")
     */
    private $contract;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item")
     */
    private $scenario;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Identification", inversedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $identification;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Circumstance", inversedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $circumstance;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\VehiculeDamage", inversedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $vehiculeDamage;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Tiers", inversedBy="preDeclaration", cascade={"persist", "remove"})
     */
    private $tiers;

    public function getId()
    {
        return $this->id;
    }

    public function getNbVehicule(): ?int
    {
        return $this->nb_vehicule;
    }

    public function setNbVehicule(int $nb_vehicule): self
    {
        $this->nb_vehicule = $nb_vehicule;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getScenario(): ?Item
    {
        return $this->scenario;
    }

    public function setScenario(?Item $scenario): self
    {
        $this->scenario = $scenario;

        return $this;
    }

    public function getIdentification(): ?Identification
    {
        return $this->identification;
    }

    public function setIdentification(?Identification $identification): self
    {
        $this->identification = $identification;

        return $this;
    }

    public function getCircumstance(): ?Circumstance
    {
        return $this->circumstance;
    }

    public function setCircumstance(?Circumstance $circumstance): self
    {
        $this->circumstance = $circumstance;

        return $this;
    }

    public function getVehiculeDamage(): ?VehiculeDamage
    {
        return $this->vehiculeDamage;
    }

    public function setVehiculeDamage(?VehiculeDamage $vehiculeDamage): self
    {
        $this->vehiculeDamage = $vehiculeDamage;

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
