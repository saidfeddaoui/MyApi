<?php

namespace App\ParamConverter;

use App\Entity\InsuranceType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InsuranceTypeParamConverter implements ParamConverterInterface
{

    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * InsuranceTypeParamConverter constructor.
     * @param ObjectManager $em
     * @param SessionInterface $session
     */
    public function __construct(ObjectManager $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request $request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {


        $token = $request->headers->get('Authorization');
        var_dump($token);
        if ($insuranceType = $this->session->get('insuranceType')) {
            $this->em->getRepository('App:InsuranceType')->find($insuranceType);
            $request->attributes->set($configuration->getName(), $insuranceType);
            return true;
        }
        if (!$request->headers->has('x-entity')) {
            return false;
        }
        $insuranceName = $request->headers->get('x-entity');
        $insuranceType = $this->em->getRepository('App:InsuranceType')->findOneByName(strtoupper($insuranceName));
        if (!$insuranceType) {
            throw new NotFoundHttpException('Requested Insurance Name Does Not exist');
        }
        $request->attributes->set($configuration->getName(), $insuranceType);
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
        return
            InsuranceType::class === $configuration->getClass()
            && array_key_exists('converter', $configuration->getOptions())
            && static::class === $configuration->getOptions()['converter'];
    }

}