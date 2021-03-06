<?php

namespace App\Security;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiUserProvider implements UserProviderInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * ApiUserProvider constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
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
        $user = $this->manager->getRepository('App:Client')->findOneByPhoneOrEmail($username);
        if (!$user) {
            throw new UsernameNotFoundException();
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
     * @param UserInterface $user
     *
     * @throws UnsupportedUserException if the user is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
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
        return Client::class === $class;
    }

}