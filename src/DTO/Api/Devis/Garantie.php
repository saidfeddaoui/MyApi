<?php

namespace App\DTO\Api\Devis;

use JMS\Serializer\Annotation as Serializer;

class Garantie
{


    /**
     * @Serializer\SerializedName("Id")
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={"response_auto","response_mrh"})
     * @var string
     */
    private $id_devis;

    /**
     * @Serializer\SerializedName("IdGarantie")
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={"response_auto","response_mrh"})
     * @var string
     */
    private $id_garantie;
    /**
     * @Serializer\SerializedName("Garantie")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var string
     */
    private $nom;
    /**
     * @Serializer\SerializedName("Acquise")
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={"response_auto","response_mrh"})
     * @var string
     */
    private $acquise;
    /**
     * @Serializer\SerializedName("LibOption")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var string
     */
    private $liboption;
    /**
     * @Serializer\SerializedName("Franchise")
     * @Serializer\Type("double")
     * @Serializer\Groups(groups={"response_auto","response_mrh"})
     * @var double
     */
    private $franchise;
    /**
     * @Serializer\SerializedName("FranchiseMin")
     * @Serializer\Type("double")
     * @Serializer\Groups("response_auto")
     * @var double
     */

    /**
     * @Serializer\SerializedName("Obligatoire")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var string
     */
    private $obligatoire;

    /**
     * @return string
     */
    public function getObligatoire()
    {
        return $this->obligatoire;
    }

    /**
     * @param string $obligatoire
     */
    public function setObligatoire($obligatoire)
    {
        $this->obligatoire = $obligatoire;
    }

    /**
     * @return string
     */
    public function getisValeurAssuree()
    {
        return $this->isValeurAssuree;
    }

    /**
     * @param string $isValeurAssuree
     */
    public function setIsValeurAssuree($isValeurAssuree)
    {
        $this->isValeurAssuree = $isValeurAssuree;
    }

    /**
     * @return string
     */
    public function getValeurAssuree()
    {
        return $this->valeurAssuree;
    }

    /**
     * @param string $valeurAssuree
     */
    public function setValeurAssuree($valeurAssuree)
    {
        $this->valeurAssuree = $valeurAssuree;
    }

    /**
     * @Serializer\SerializedName("IsValeurAssuree")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var string
     */
    private $isValeurAssuree;

    /**
     * @Serializer\SerializedName("ValeurAssuree")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_auto")
     * @var string
     */
    private $valeurAssuree;

    /**
     * @Serializer\SerializedName("Capital")
     * @Serializer\Type("string")
     * @Serializer\Groups("response_mrh")
     * @var string
     */
    private $capital;

    /**
     * @Serializer\SerializedName("LibelleGarantie")
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={"response_auto","response_mrh"})
     * @var string
     */
    private $libelle_garantie;

    /**
     * @Serializer\SerializedName("IdDet")
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={"response_auto","response_mrh"})
     * @var string
     */
    private $id_det;

    /**
     * @Serializer\SerializedName("ListOption")
     * @Serializer\Type("array<App\DTO\Api\Devis\Option>")
     * @Serializer\Groups("response_auto")
     * @var Options[]
     */
    private $options;

    /**
     * @return string
     */
    public function getIdDevis()
    {
        return $this->id_devis;
    }

    /**
     * @param string $id_devis
     * @return Garantie
     */
    public function setIdDevis($id_devis)
    {
        $this->id_devis = $id_devis;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdGarantie()
    {
        return $this->id_garantie;
    }

    /**
     * @param string $id_garantie
     * @return Garantie
     */
    public function setIdGarantie($id_garantie)
    {
        $this->id_garantie = $id_garantie;
        return $this;
    }

    /**
     * @return Options[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Options[] $options
     * @return Garantie
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Garantie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getAcquise()
    {
        return $this->acquise;
    }

    /**
     * @param string $acquise
     * @return Garantie
     */
    public function setAcquise($acquise)
    {
        $this->acquise = $acquise;
        return $this;
    }

    /**
     * @return string
     */
    public function getLiboption()
    {
        return $this->liboption;
    }

    /**
     * @param string $liboption
     * @return Garantie
     */
    public function setLiboption($liboption)
    {
        $this->liboption = $liboption;
        return $this;
    }

    /**
     * @return float
     */
    public function getFranchise()
    {
        return $this->franchise;
    }

    /**
     * @param float $franchise
     * @return Garantie
     */
    public function setFranchise($franchise)
    {
        $this->franchise = $franchise;
        return $this;
    }

    /**
     * @return string
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * @param string $capital
     * @return Garantie
     */
    public function setCapital($capital)
    {
        $this->capital = $capital;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleGarantie()
    {
        return $this->libelle_garantie;
    }

    /**
     * @param string $libelle_garantie
     * @return Garantie
     */
    public function setLibelleGarantie($libelle_garantie)
    {
        $this->libelle_garantie = $libelle_garantie;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdDet()
    {
        return $this->id_det;
    }

    /**
     * @param string $id_det
     * @return Garantie
     */
    public function setIdDet($id_det)
    {
        $this->id_det = $id_det;
        return $this;
    }




}