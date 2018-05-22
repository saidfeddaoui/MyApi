<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

//    /**
//     * @var Role $role
//     *
//     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users", cascade={"persist", "merge"})
//     * @ORM\JoinColumns({
//     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
//     * })
//     */
//    private $role;

    public function __construct()
    {
        parent::__construct();

        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->locked = false;
        $this->expired = false;
        $this->enabled = true;
        $this->credentialsExpired = false;
        $this->roles = array('ROLE_MODERATEUR');
    }


    public function getId()
    {
        return $this->id;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }


}
