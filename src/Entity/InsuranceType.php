<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role as BaseRole;
use JMS\Serializer\Annotation as Serializer;
/**
 * @ORM\Entity(repositoryClass="App\Repository\InsuranceTypeRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class InsuranceType extends BaseRole
{
    /**
     * @Serializer\Expose()
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $theme_color;

    /**
     * @Serializer\Expose()
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="insuranceTypes")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Accident", mappedBy="insuranceType")
     */
    private $accidents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Alert", mappedBy="insuranceType")
     */
    private $alerts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ItemList", mappedBy="insuranceType")
     */
    private $itemLists;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MarqueVehicule", mappedBy="insuranceType")
     */
    private $marqueVehicules;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhotosSinistre", mappedBy="insuranceType")
     */
    private $photosSinistres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ville", mappedBy="insuranceType")
     */
    private $villes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CirconstanceSinistre", mappedBy="insuranceType")
     */
    private $circonstanceSinistres;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->accidents = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->itemLists = new ArrayCollection();
        $this->marqueVehicules = new ArrayCollection();
        $this->photosSinistres = new ArrayCollection();
        $this->villes = new ArrayCollection();
        $this->circonstanceSinistres = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getRole();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string
     */
    public function getThemeColor()
    {
        return $this->theme_color;
    }

    /**
     * @param string $theme_color
     * @return static
     */
    public function setThemeColor($theme_color)
    {
        $this->theme_color = $theme_color;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addInsuranceType($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeInsuranceType($this);
        }

        return $this;
    }

    /**
     * @return Collection|Accident[]
     */
    public function getAccidents(): Collection
    {
        return $this->accidents;
    }

    public function addAccident(Accident $accident): self
    {
        if (!$this->accidents->contains($accident)) {
            $this->accidents[] = $accident;
            $accident->setInsuranceType($this);
        }

        return $this;
    }

    public function removeAccident(Accident $accident): self
    {
        if ($this->accidents->contains($accident)) {
            $this->accidents->removeElement($accident);
            // set the owning side to null (unless already changed)
            if ($accident->getInsuranceType() === $this) {
                $accident->setInsuranceType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setInsuranceType($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->contains($alert)) {
            $this->alerts->removeElement($alert);
            // set the owning side to null (unless already changed)
            if ($alert->getInsuranceType() === $this) {
                $alert->setInsuranceType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ItemList[]
     */
    public function getItemLists(): Collection
    {
        return $this->itemLists;
    }

    public function addItemList(ItemList $itemList): self
    {
        if (!$this->itemLists->contains($itemList)) {
            $this->itemLists[] = $itemList;
            $itemList->setInsuranceType($this);
        }

        return $this;
    }

    public function removeItemList(ItemList $itemList): self
    {
        if ($this->itemLists->contains($itemList)) {
            $this->itemLists->removeElement($itemList);
            // set the owning side to null (unless already changed)
            if ($itemList->getInsuranceType() === $this) {
                $itemList->setInsuranceType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MarqueVehicule[]
     */
    public function getMarqueVehicules(): Collection
    {
        return $this->marqueVehicules;
    }

    public function addMarqueVehicule(MarqueVehicule $marqueVehicule): self
    {
        if (!$this->marqueVehicules->contains($marqueVehicule)) {
            $this->marqueVehicules[] = $marqueVehicule;
            $marqueVehicule->setInsuranceType($this);
        }

        return $this;
    }

    public function removeMarqueVehicule(MarqueVehicule $marqueVehicule): self
    {
        if ($this->marqueVehicules->contains($marqueVehicule)) {
            $this->marqueVehicules->removeElement($marqueVehicule);
            // set the owning side to null (unless already changed)
            if ($marqueVehicule->getInsuranceType() === $this) {
                $marqueVehicule->setInsuranceType(null);
            }
        }

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
            $photosSinistre->setInsuranceType($this);
        }

        return $this;
    }

    public function removePhotosSinistre(PhotosSinistre $photosSinistre): self
    {
        if ($this->photosSinistres->contains($photosSinistre)) {
            $this->photosSinistres->removeElement($photosSinistre);
            // set the owning side to null (unless already changed)
            if ($photosSinistre->getInsuranceType() === $this) {
                $photosSinistre->setInsuranceType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ville[]
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }

    public function addVille(Ville $ville): self
    {
        if (!$this->villes->contains($ville)) {
            $this->villes[] = $ville;
            $ville->setInsuranceType($this);
        }

        return $this;
    }

    public function removeVille(Ville $ville): self
    {
        if ($this->villes->contains($ville)) {
            $this->villes->removeElement($ville);
            // set the owning side to null (unless already changed)
            if ($ville->getInsuranceType() === $this) {
                $ville->setInsuranceType(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|circonstanceSinistre[]
     */
    public function getCirconstanceSinistres(): Collection
    {
        return $this->circonstanceSinistres;
    }

    public function addCirconstanceSinistre(CirconstanceSinistre $circonstanceSinistre): self
    {
        if (!$this->circonstanceSinistres->contains($circonstanceSinistre)) {
            $this->circonstanceSinistres[] = $circonstanceSinistre;
            $circonstanceSinistre->setInsuranceType($this);
        }

        return $this;
    }

    public function removeCirconstanceSinistre(CirconstanceSinistre $circonstanceSinistre): self
    {
        if ($this->circonstanceSinistres->contains($circonstanceSinistre)) {
            $this->circonstanceSinistres->removeElement($circonstanceSinistre);
            // set the owning side to null (unless already changed)
            if ($circonstanceSinistre->getInsuranceType() === $this) {
                $circonstanceSinistre->setInsuranceType(null);
            }
        }

        return $this;
    }
}
