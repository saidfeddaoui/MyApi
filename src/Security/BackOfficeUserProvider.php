<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class BackOfficeUserProvider implements UserProviderInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var CanonicalFieldsUpdater
     */
    private $canonicalFieldsUpdater;

    /**
     * BackOfficeUserProvider constructor.
     *
     * @param EntityManagerInterface $em
     * @param CanonicalFieldsUpdater $canonicalFieldsUpdater
     */
    public function __construct(EntityManagerInterface $em, CanonicalFieldsUpdater $canonicalFieldsUpdater)
    {
        $this->em = $em;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->em->getRepository('App:User')->findOneByCanonicalUsername(
            $this->canonicalFieldsUpdater->canonicalizeUsername($username)
        );
        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
        return $user;
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param  UserInterface $user
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the user is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException(
                sprintf('Expected an instance of "%s", but got "%s".', User::class, get_class($user))
            );
        }
        if (null === $reloadedUser = $this->em->getRepository('App:User')->findOneById($user->getId())) {
            throw new UsernameNotFoundException(sprintf('User with ID "%s" could not be reloaded.', $user->getId()));
        }
        return $reloadedUser;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }

}