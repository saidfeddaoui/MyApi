<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role as BaseRole;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role extends BaseRole
{

    const MOBILE_CLIENT = 'ROLE_MOBILE_CLIENT';
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
     * @ORM\Column(name="role", type="string", length=60, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="roles")
     */
    private $users;

    /**
     * Role constructor.
     * @param string $name
     * @param string $role
     */
    public function __construct(?string $name = null, ?string $role = null)
    {
        $this->name = $name;
        $this->role = $role;
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }
    /**
     * @param string $role
     * @return self
     */
    public function setRole($role): self
    {
        $this->role = $role;
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
            $user->addRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeRole($this);
        }

        return $this;
    }

}
