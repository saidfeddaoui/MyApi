<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role as BaseRole;

/**
 * @ORM\Table(name="groups")
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group extends BaseRole
{

    const ADMIN = 'ROLE_ADMIN';
    const USER = 'ROLE_USER';
    const MOBILE_USER = 'ROLE_MOBILE_USER';
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
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $role;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="_group")
     */
    private $users;
    /**
     * @var Group
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", inversedBy="children")
     * @ORM\JoinTable(
     *     name="groups_hierarchy",
     *     joinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")}
     * )
     **/
    private $parents;
    /**
     * @var Collection|Group[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", mappedBy="parents")
     */
    private $children;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     */
    private $roles;

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
        $this->parents = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->roles = new ArrayCollection();
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

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return Group
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    /**
     * @return null|string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }
    /**
     * @param string $role
     * @return Group
     */
    public function setRole(string $role): self
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
    /**
     * @param User $user
     * @return Group
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGroup($this);
        }
        return $this;
    }
    /**
     * @param User $user
     * @return Group
     */
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getGroup() === $this) {
                $user->setGroup(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }
    /**
     * @param Role $role
     * @return Group
     */
    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }
        return $this;
    }
    /**
     * @param Role $role
     * @return Group
     */
    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
        return $this;
    }
    /**
     * @return Collection|Group[]
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }
    /**
     * @param Group $group
     * @return static
     */
    public function addParent(Group $group): self
    {
        if (!$this->parents->contains($group)) {
            $this->parents[] = $group;
        }
        return $this;
    }
    /**
     * @param Group $group
     * @return static
     */
    public function removeParent(Group $group): self
    {
        if ($this->parents->contains($group)) {
            $this->parents->removeElement($group);
        }
        return $this;
    }
    /**
     * @return Collection|Group[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }
    /**
     * @param Group $child
     * @return Group
     */
    public function addChild(Group $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->addParent($this);
        }
        return $this;
    }
    /**
     * @param Group $child
     * @return Group
     */
    public function removeChild(Group $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            $child->removeParent($this);
        }
        return $this;
    }

}
