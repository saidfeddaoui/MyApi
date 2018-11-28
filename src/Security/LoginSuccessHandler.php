<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    use TargetPathTrait;

    /**
     * @var Router
     */
    protected $router;
    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * LoginSuccessHandler constructor.
     * @param Router $router
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(Router $router, AuthorizationChecker $authorizationChecker)
    {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
//        $response = null;
//        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
//            $response = new RedirectResponse($this->router->generate('pre_declarations_in_progress'));
//        } else if ($this->authorizationChecker->isGranted('ROLE_USER')) {
//            $response = new RedirectResponse($this->router->generate('pre_declarations_in_progress'));
//        }
        /**
         * @var User $user
         */
        $user = $token->getUser();
        if ($mutuelle = $user->getInsuranceTypes()->first()) {
            $request->getSession()->set('insuranceType', $mutuelle);
        }
        if ($url = $this->getTargetPath($request->getSession(), 'main')) {
            return new RedirectResponse($url);
        }
        return new RedirectResponse($this->router->generate('home'));
    }

}