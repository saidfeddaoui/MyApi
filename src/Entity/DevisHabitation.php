<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class DevisHabitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $civilite;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_auto"})
     * @Assert\Regex(pattern="/^0[0-9]{9}$/", groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $tel;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("devis_mrh")
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     * @Assert\Email(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh"})
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="double", nullable = true)
     */
    private $batiment;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh"})
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $contenu;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh"})
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $categorie;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"devis_mrh"})
     *
     * @Assert\NotNull(groups={"devis_mrh"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $propriete;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return DevisHabitation
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     * @return DevisHabitation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     * @return DevisHabitation
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * @param mixed $civilite
     * @return DevisHabitation
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     * @return DevisHabitation
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
        return $this;
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
     * @return DevisHabitation
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBatiment()
    {
        return $this->batiment;
    }

    /**
     * @param mixed $batiment
     * @return DevisHabitation
     */
    public function setBatiment($batiment)
    {
        $this->batiment = $batiment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @param mixed $contenu
     * @return DevisHabitation
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     * @return DevisHabitation
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPropriete()
    {
        return $this->propriete;
    }

    /**
     * @param mixed $propriete
     * @return DevisHabitation
     */
    public function setPropriete($propriete)
    {
        $this->propriete = $propriete;
        return $this;
    }


}
