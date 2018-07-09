<?php

namespace App\ParamConverter;

use App\Entity\Client;
use Doctrine\Common\Persistence\ObjectManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationTokenParamConverter implements ParamConverterInterface
{

    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var JWTEncoderInterface
     */
    private $encoder;
    /**
     * RegistrationTokenParamConverter constructor.
     * @param ObjectManager $em
     * @param JWTEncoderInterface $encoder
     */
    public function __construct(ObjectManager $em, JWTEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request $request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if (!$request->headers->has('x-registration-token')) {
            return false;
        }
        $token = $request->headers->get('x-registration-token');
        $jwt = $this->encoder->decode($token);
        $client = $this->em->getRepository('App:Client')->findOneByPhone($jwt['phone']);
        if (!$client) {
            throw new NotFoundHttpException('No client was found');
        }
        $request->attributes->set($configuration->getName(), $client);
        return true;
    }
    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Contains the name, class and options of the object
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return Client::class === $configuration->getClass() && static::class === $configuration->getOptions()['converter'];
    }

}