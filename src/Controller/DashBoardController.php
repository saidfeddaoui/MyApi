<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DashBoardController extends Controller
{

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * DashBoardController constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->redirect($this->generateUrl('pre_declarations_in_progress'));
    }

}