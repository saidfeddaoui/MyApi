<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviGarantiesRepository")
 */
class DeviGaranties
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deviMamda;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $garantie;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $acquise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libOption;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $franchise;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $obligatoire;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $isValeurAssuree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $valeurAssuree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelleGarantie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $det;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $devis;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $type;

    public function getId()
    {
        return $this->id;
    }

    public function getDeviMamda(): ?int
    {
        return $this->deviMamda;
    }

    public function setDeviMamda(?int $deviMamda): self
    {
        $this->deviMamda = $deviMamda;

        return $this;
    }

    public function getGarantie(): ?string
    {
        return $this->garantie;
    }

    public function setGarantie(?string $garantie): self
    {
        $this->garantie = $garantie;

        return $this;
    }

    public function getAcquise(): ?string
    {
        return $this->acquise;
    }

    public function setAcquise(?string $acquise): self
    {
        $this->acquise = $acquise;

        return $this;
    }

    public function getLibOption(): ?string
    {
        return $this->libOption;
    }

    public function setLibOption(?string $libOption): self
    {
        $this->libOption = $libOption;

        return $this;
    }

    public function getFranchise(): ?int
    {
        return $this->franchise;
    }

    public function setFranchise(?int $franchise): self
    {
        $this->franchise = $franchise;

        return $this;
    }

    public function getObligatoire(): ?string
    {
        return $this->obligatoire;
    }

    public function setObligatoire(?string $obligatoire): self
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }

    public function getIsValeurAssuree(): ?string
    {
        return $this->isValeurAssuree;
    }

    public function setIsValeurAssuree(?string $isValeurAssuree): self
    {
        $this->isValeurAssuree = $isValeurAssuree;

        return $this;
    }

    public function getValeurAssuree(): ?string
    {
        return $this->valeurAssuree;
    }

    public function setValeurAssuree(?string $valeurAssuree): self
    {
        $this->valeurAssuree = $valeurAssuree;

        return $this;
    }

    public function getLibelleGarantie(): ?string
    {
        return $this->libelleGarantie;
    }

    public function setLibelleGarantie(?string $libelleGarantie): self
    {
        $this->libelleGarantie = $libelleGarantie;

        return $this;
    }

    public function getDet(): ?string
    {
        return $this->det;
    }

    public function setDet(?string $det): self
    {
        $this->det = $det;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }


    public function getDevis():?int
    {
        return $this->devis;
    }

    public function setDevis(?int $devis): self
    {
        $this->devis = $devis;
        return $this;
    }
}
