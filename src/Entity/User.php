<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\Role\Role as BaseRole;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"include_id","listPreDeclaration"})
     * @Serializer\Groups({"client_account_creation","login_response","client_pre_declaration"})
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $username;
    /**
     * @var string
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $usernameCanonical;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;
    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_account_creation","login_response"})
     *
     * @Assert\NotBlank(groups={"client_account_creation"})
     * @Assert\Email(groups={"client_account_creation"})
     *
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    protected $email;
    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    protected $emailCanonical;
    /**
     * The salt to use for hashing.
     *
     * @var string
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    protected $salt;
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $enabled = true;
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $accountNonExpired = true;
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $credentialsNonExpired = true;
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $accountNonLocked = true;
    /**
     * Plain password. Used for model validation. Must not be persisted.
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"client_account_creation"})
     *
     * @Assert\NotBlank(groups={"client_account_creation"})
     */
    protected $plainPassword;
    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;
    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $confirmationToken;
    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;
    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="users")
     */
    protected $roles;
    /**
     * @var Group
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="users")
     */
    protected $_group;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\InsuranceType", inversedBy="users", cascade={"persist"})
     */
    protected $insuranceTypes;

    public function __construct(?string $username = null, ?string $password = null, bool $enabled = true, bool $userNonExpired = true, bool $credentialsNonExpired = true, bool $userNonLocked = true)
    {
        $this->username = $username;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->accountNonExpired = $userNonExpired;
        $this->credentialsNonExpired = $credentialsNonExpired;
        $this->accountNonLocked = $userNonLocked;
        $this->salt = sha1(random_bytes(30));
        $this->roles = new ArrayCollection();
        $this->insuranceTypes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getUsername();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }
    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonExpired;
    }
    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }
    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->password,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
            $this->email,
            $this->emailCanonical,
            $this->roles,
            $this->_group,
            $this->insuranceTypes,
        ]);
    }
    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        list(
            $this->password,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
            $this->email,
            $this->emailCanonical,
            $this->roles,
            $this->_group,
            $this->insuranceTypes,
        ) = $data;
    }
    /**
     * Sets the username.
     *
     * @param string $username
     *
     * @return static
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    /**
     * Gets the canonical username in search and sort queries.
     *
     * @return string
     */
    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }
    /**
     * Sets the canonical username.
     *
     * @param string $usernameCanonical
     *
     * @return static
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        $this->usernameCanonical = $usernameCanonical;
        return $this;
    }
    /**
     * @param string|null $salt
     *
     * @return static
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }
    /**
     * Gets email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return static
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    /**
     * Gets the canonical email in search and sort queries.
     *
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }
    /**
     * Sets the canonical email.
     *
     * @param string $emailCanonical
     *
     * @return static
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
        return $this;
    }
    /**
     * Gets the plain password.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    /**
     * Sets the plain password.
     *
     * @param string $password
     *
     * @return static
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
        return $this;
    }
    /**
     * Sets the hashed password.
     *
     * @param string $password
     *
     * @return static
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    /**
     * Tells if the the given user has the super admin role.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        // TODO: Implement isSuperAdmin() method.
    }
    /**
     * Gets the confirmation token.
     *
     * @return string|null
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }
    /**
     * Sets the last login time.
     *
     * @param \DateTime|null $time
     *
     * @return static
     */
    public function setLastLogin(\DateTime $time = null)
    {
        $this->lastLogin = $time;
        return $this;
    }
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }
    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
    /**
     * @param bool $boolean
     *
     * @return static
     */
    public function setEnabled($boolean)
    {
        $this->enabled = $boolean;
        return $this;
    }
    /**
     * Sets the super admin status.
     *
     * @param bool $boolean
     *
     * @return static
     */
    public function setSuperAdmin($boolean)
    {
        // TODO: Implement setSuperAdmin() method.
    }
    /**
     * Sets the confirmation token.
     *
     * @param string|null $confirmationToken
     *
     * @return static
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }
    /**
     * Sets the timestamp that the user requested a password reset.
     *
     * @param null|\DateTime $date
     *
     * @return static
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;
        return $this;
    }
    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Checks whether the password reset request has expired.
     *
     * @param int $ttl Requests older than this many seconds will be considered expired
     *
     * @return bool
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
            $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * @return Role[]
     */
    public function getRoles()
    {
        return array_merge($this->roles->toArray(), $this->insuranceTypes->toArray(), [$this->_group]);
    }
    /**
     * @return string[]
     */
    public function getRolesNames()
    {
        return array_map(function ($role) {return $role->getRole();}, $this->getRoles());
    }
    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the AuthorizationChecker, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $authorizationChecker->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->roles->contains($role);
    }
    /**
     * Sets the roles of the user.
     *
     * This overwrites any previous roles.
     *
     * @param array $roles
     *
     * @return static
     */
    public function setRoles(array $roles)
    {
        foreach ($roles as $role) {
            $this->_addRole($role);
        }
        return $this;
    }
    /**
     * Adds a role to the user.
     *
     * @param string $role
     *
     * @return static
     */
    public function addRole($role)
    {
        return $this->_addRole($role);
    }
    /**
     * Removes a role to the user.
     *
     * @param string $role
     *
     * @return static
     */
    public function removeRole($role)
    {
        return $this->_removeRole($role);
    }
    /**
     * @return Collection
     */
    public function _getRoles(): Collection
    {
        return $this->roles;
    }
    /**
     * @param BaseRole $role
     * @return static
     */
    public function _addRole(BaseRole $role): self
    {
        if (!$this->roles) {
            $this->roles = new ArrayCollection();
        }
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }
        return $this;
    }
    /**
     * @param BaseRole $role
     * @return static
     */
    public function _removeRole(BaseRole $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }
        return $this;
    }
    /**
     * @return Group
     */
    public function getGroup(): ?Group
    {
        return $this->_group;
    }
    /**
     * @param Group
     * @return static
     */
    public function setGroup(?Group $_group): self
    {
        $this->_group = $_group;
        return $this;
    }
    /**
     * @return Collection|InsuranceType[]
     */
    public function getInsuranceTypes(): Collection
    {
        return $this->insuranceTypes;
    }

    /**
     * @param ArrayCollection|InsuranceType $insuranceTypes
     * @return static
     */
    public function setInsuranceTypes($insuranceTypes): self
    {
        if ($insuranceTypes instanceof InsuranceType) {
            $insuranceTypes = new ArrayCollection([$insuranceTypes]);
        }
        $this->insuranceTypes = $insuranceTypes;
        return $this;
    }
    /**
     * @param InsuranceType $insuranceType
     * @return User
     */
    public function addInsuranceType(InsuranceType $insuranceType): self
    {
        if (!$this->insuranceTypes) {
            $this->insuranceTypes = new ArrayCollection();
        }
        if (!$this->insuranceTypes->contains($insuranceType)) {
            $this->insuranceTypes[] = $insuranceType;
        }
        return $this;
    }
    /**
     * @param InsuranceType $insuranceType
     * @return User
     */
    public function removeInsuranceType(InsuranceType $insuranceType): self
    {
        if (!$this->insuranceTypes) {
            $this->insuranceTypes = new ArrayCollection();
        }
        if ($this->insuranceTypes->contains($insuranceType)) {
            $this->insuranceTypes->removeElement($insuranceType);
        }
        return $this;
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * Also implementation should consider that $user instance may implement
     * the extended user interface `AdvancedUserInterface`.
     *
     * @var \Symfony\Component\Security\Core\User\UserInterface $user
     * @return bool
     */
    public function isEqualTo(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        if ($this->username !== $user->getUsername() || $this->password !== $user->getPassword()) {
            return false;
        }
        return true;
    }


}
