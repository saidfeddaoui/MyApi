<?php

namespace App\Security;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class RoleHierarchy implements RoleHierarchyInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var array
     */
    private $map = [];

    /**
     * RoleHierarchy constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->buildRoleMap();
    }

    /**
     * Returns an array of all reachable roles by the given ones.
     *
     * Reachable roles are the roles directly assigned but also all roles that
     * are transitively reachable from them in the role hierarchy.
     *
     * @param Role[] $roles An array of directly assigned roles
     *
     * @return Role[] An array of all reachable roles
     */
    public function getReachableRoles(array $roles)
    {
        $reachableRoles = $roles;
        foreach ($roles as $role) {
            if (!array_key_exists($role->getRole(), $this->map)) {
                continue;
            }
            foreach ($this->map[$role->getRole()] as $_role) {
                $reachableRoles[] = $_role;
            }
        }
        return $reachableRoles;
    }

    /**
     * @return void
     */
    protected function buildRoleMap()
    {
        $hierarchy = $this->getHierarchy();
        foreach ($hierarchy as $main => $roles) {
            $this->map[$main] = $roles;
            $visited = [];
            $additionalRoles = $roles;
            while ($role = array_shift($additionalRoles)) {
                if (!$hierarchy[$role->getRole()]) {
                    continue;
                }
                $visited[] = $role->getRole();
                foreach ($hierarchy[$role->getRole()] as $roleToAdd) {
                    if ($main === $roleToAdd->getRole()) {
                        continue;
                    }
                    $this->map[$main][] = $roleToAdd;
                }
                foreach (array_diff($hierarchy[$role->getRole()], $visited) as $additionalRole) {
                    $additionalRoles[] = $additionalRole;
                }
            }
            $this->map[$main] = array_unique($this->map[$main]);
        }
    }
    /**
     * @return array
     */
    protected function getHierarchy()
    {
        $hierarchy = [];
        $roles = $this->em->getRepository('App:Role')->findAll();
        foreach ($roles as $role) {
            if (!array_key_exists($role->getRole(), $hierarchy)) {
                $hierarchy[$role->getRole()] = [];
            }
            foreach ($role->getParents() as $parent) {
                if (array_key_exists($parent->getRole(), $hierarchy) && in_array($role, $hierarchy[$parent->getRole()])) {
                    continue;
                }
                $hierarchy[$parent->getRole()][] = $role;
            }
        }
        $groups = $this->em->getRepository('App:Group')->findAll();
        foreach ($groups as $group) {
            if (!array_key_exists($group->getRole(), $hierarchy)) {
                $hierarchy[$group->getRole()] = [];
            }
            foreach ($group->getParents() as $parent) {
                if (array_key_exists($parent->getRole(), $hierarchy) && in_array($group, $hierarchy[$parent->getRole()])) {
                    continue;
                }
                $hierarchy[$parent->getRole()][] = $group;
            }
            foreach ($group->getRoles() as $role) {
                if (in_array($role, $hierarchy[$group->getRole()])) {
                    continue;
                }
                $hierarchy[$group->getRole()][] = $role;
            }
        }
        return $hierarchy;
    }

}