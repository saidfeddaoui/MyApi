<?php

namespace App\ParamConverter;

use App\Entity\Societaire;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SocietaireParamConverter implements ParamConverterInterface
{

    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * SocietaireParamConverter constructor.
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
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
        if (!$request->headers->has('X-CODE')) {
            return false;
        }
        $societaire = $request->headers->get('X-CODE');
        $societaireType = $this->em->getRepository('App:Societaire')->findOneByCode(strtoupper($societaire));
        if (!$societaireType) {
            throw new NotFoundHttpException('Requested societaire code Does Not exist');
        }
        $request->attributes->set($configuration->getName(), $societaireType);
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
            Societaire::class === $configuration->getClass()
            && array_key_exists('converter', $configuration->getOptions())
            && static::class === $configuration->getOptions()['converter'];
    }

}