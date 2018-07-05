<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role as BaseRole;
/**
 * @ORM\Entity(repositoryClass="App\Repository\InsuranceTypeRepository")
 */
class InsuranceType extends BaseRole
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="insuranceTypes")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Accident", mappedBy="insuranceType")
     */
    private $accidents;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->accidents = new ArrayCollection();
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
}
