<?php

namespace App\Security;

use App\DTO\Api\ApiResponse;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class JwtAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{

    /**
     * @var TokenExtractorInterface
     */
    private $extractor;
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * JwtAuthenticator constructor.
     * @param TokenExtractorInterface $extractor
     * @param SerializerInterface $serializer
     * @param JWTEncoderInterface $jwtEncoder
     */
    public function __construct(TokenExtractorInterface $extractor, SerializerInterface $serializer, JWTEncoderInterface $jwtEncoder)
    {
        $this->extractor = $extractor;
        $this->serializer = $serializer;
        $this->jwtEncoder = $jwtEncoder;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $credentials = $this->jwtEncoder->decode($token->getCredentials());
        $username = $credentials['phone'] ?? null;
        if (!$username) {
            throw new AuthenticationCredentialsNotFoundException();
        }
        $user = $userProvider->loadUserByUsername($username);
        return new PreAuthenticatedToken($user, $credentials, $providerKey, $user->getRoles());
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken|void
     */
    public function createToken(Request $request, $providerKey)
    {
        $jwtToken = $this->extractor->extract($request);
        if (!$jwtToken) {
            return;
        }
        return new PreAuthenticatedToken('anon.', $jwtToken, $providerKey);
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $response = new ApiResponse(null, ApiResponse::AUTHENTICATION_FAILURE);
        return new JsonResponse($this->serializer->serialize($response, 'json'), $response->getHttpStatusCode(), [], true);
    }
}