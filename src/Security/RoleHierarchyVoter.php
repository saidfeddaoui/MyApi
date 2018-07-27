<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter as BaseRoleHierarchyVoter;

class RoleHierarchyVoter extends BaseRoleHierarchyVoter
{

    /**
     * @var RoleHierarchy
     */
    protected $roleHierarchy;

    /**
     * RoleHierarchyVoter constructor.
     * @param RoleHierarchy $roleHierarchy
     */
    public function __construct(RoleHierarchy $roleHierarchy)
    {
        parent::__construct($this->roleHierarchy = $roleHierarchy);
    }

}