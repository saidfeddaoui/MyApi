<?php

namespace App\ParamConverter;

use App\Entity\PreDeclaration;
use App\Exception\ConstraintViolationException;
use App\Exception\DuplicatedPreDeclarationException;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PreDeclarationParamConverter implements ParamConverterInterface
{

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PreDeclarationParamConverter constructor.
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->em = $em;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request $request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     * @throws ConstraintViolationException
     * @throws DuplicatedPreDeclarationException
     * @throws NotFoundHttpException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        /**
         * @var PreDeclaration $preDeclaration
         */
        $preDeclaration = $this->serializer->deserialize($request->getContent(), PreDeclaration::class, 'json');
        $options = $configuration->getOptions();
        if (array_key_exists('validator', $options)) {
            $groups = array_key_exists('groups', $options['validator']) ? $options['validator']['groups'] : null;
            $violations = $this->validator->validate($preDeclaration, null, $groups);
            if ($violations->count()) {
                throw new ConstraintViolationException($violations);
            }
        }
        $_preDeclaration = $this->em->getRepository('App:PreDeclaration')->findBy([
            'contrat' => $preDeclaration->getContrat(),
            'dateSinistre' => $preDeclaration->getDateSinistre(),
        ]);
        if ($_preDeclaration) {
            throw new DuplicatedPreDeclarationException();
        }
        $this->processPreDeclaration($preDeclaration);
        $request->attributes->set($configuration->getName(), $preDeclaration);
        return true;
    }
    /**
     * @param PreDeclaration $preDeclaration
     * @throws NotFoundHttpException
     */
    private function processPreDeclaration(PreDeclaration $preDeclaration)
    {
        $this->processContrat($preDeclaration);
        $this->processTypeSinistre($preDeclaration);
      //  $this->processVille($preDeclaration);
      //  $this->processCircumstanceAttachments($preDeclaration);
       // $type = $preDeclaration->getTypeSinistre()->getTitle();
        $this->processCirconstanceSinistre($preDeclaration);

        $this->processDamagedParts($preDeclaration);
        $this->processTiersAttachments($preDeclaration);

      /*  switch ($type) {
            case PreDeclaration::TYPE_ACCIDENT:
                    $this->processDamagedParts($preDeclaration);
                    $this->processTiersAttachments($preDeclaration);
                break;
            case PreDeclaration::TYPE_BRIS_GLACE:
                    $this->processDamagedParts($preDeclaration);
                    //$preDeclaration->setTiers(null);
                    $this->processTiersAttachments($preDeclaration);
                break;
            default:
                    $preDeclaration->setTiers(null)->setVehiculeDamage(null);
                break;
        } */
    }
    /**
     * @param PreDeclaration $preDeclaration
     * @throws NotFoundHttpException
     */
    private function processContrat(PreDeclaration $preDeclaration)
    {
       // $id = $preDeclaration->getContrat()->getId();
        $police = $preDeclaration->getContrat()->getPolice();
        $contrat = $this->em->getRepository('App:Contrats')->findOneByPolice($police);
        if (!$contrat) {
            throw new NotFoundHttpException("No Contrat with reference: {$police} was found");
        }
        $preDeclaration->setContrat($contrat);
    }
    /**
     * @param PreDeclaration $preDeclaration
     * @throws NotFoundHttpException
     */
    private function processTypeSinistre(PreDeclaration $preDeclaration)
    {
        $id = $preDeclaration->getTypeSinistre()->getId();
        $typeSinistre = $this->em->getRepository('App:Item')->findOneById($id);
        if (!$typeSinistre) {
            throw new NotFoundHttpException("No Sinistre type with reference: {$id} was found");
        }
        $preDeclaration->setTypeSinistre($typeSinistre);
    }


    /**
     * @param PreDeclaration $preDeclaration
     * @throws NotFoundHttpException
     */
    private function processCirconstanceSinistre(PreDeclaration $preDeclaration)
    {
        $id = $preDeclaration->getCirconstanceSinistre()->getId();
        $circonstanceSinistre = $this->em->getRepository('App:CirconstanceSinistre')->findOneById($id);
        if (!$circonstanceSinistre) {
            throw new NotFoundHttpException("No Circonstance Sinistre with reference: {$id} was found");
        }
        $preDeclaration->setCirconstanceSinistre($circonstanceSinistre);
    }



    /**
     * @param PreDeclaration $preDeclaration
     * @throws NotFoundHttpException
     */
  /*  private function processVille(PreDeclaration $preDeclaration)
    {
        $id = $preDeclaration->getCircumstance()->getVille()->getId();
        $city = $this->em->getRepository('App:Ville')->findOneById($id);
        if (!$city) {
            throw new NotFoundHttpException("No City with reference: {$id} was found");
        }
        $preDeclaration->getCircumstance()->setVille($city);
    } */

    /**
     * @param PreDeclaration $preDeclaration
     * @throws NotFoundHttpException
     */
  /*  private function processCircumstanceAttachments(PreDeclaration $preDeclaration)
    {
        $ids = $preDeclaration->getCircumstance()
            ->getPhotos()
            ->map(function ($c) {return $c->getId();})
            ->toArray();
        $attachments = $this->em->getRepository('App:CircumstanceAttachment')->findByIds($ids);
        if ($diff = array_diff($ids, array_map(function ($c) {return $c->getId();}, $attachments))) {
            throw new NotFoundHttpException(
                "Submitted circumstance's attachments doesn't exist, (" . implode(',', $diff) . ")"
            );
        }
        $preDeclaration->getCircumstance()->setPhotos($attachments);
    }
    */
    /**
     * @param PreDeclaration $preDeclaration
     * @throws NotFoundHttpException
     */
    private function processDamagedParts(PreDeclaration $preDeclaration)
    {
        $codes = $preDeclaration->getVehiculeDamage()
            ->getDamagedParts()
            ->map(function ($c) {return $c->getCode();})
            ->toArray();
        $components = $this->em->getRepository('App:VehiculeComponent')->findByCodes($codes);
        if ($diff = array_diff($codes, array_map(function ($c) {return $c->getCode();}, $components))) {
            throw new NotFoundHttpException(
                "Submitted damaged parts doesn't exist, (" . implode(',', $diff) . ")"
            );
        }
        $preDeclaration->getVehiculeDamage()->setDamagedParts($components);
    }
    /**
     * @param PreDeclaration $preDeclaration
     * @throws NotFoundHttpException
     */
    private function processTiersAttachments(PreDeclaration $preDeclaration)
    {
        $ids = $preDeclaration->getImages()
            ->map(function ($c) {return $c->getId();})
            ->toArray();
        $attachments = $this->em->getRepository('App:TiersAttachment')->findByIds($ids);
        if ($diff = array_diff($ids, array_map(function ($c) {return $c->getId();}, $attachments))) {
            throw new NotFoundHttpException(
                "Submitted tiers attachments doesn't exist, (" . implode(',', $diff) . ")"
            );
        }
        $preDeclaration->setImages($attachments);
    }
    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return
            PreDeclaration::class === $configuration->getClass()
            && array_key_exists('converter', $configuration->getOptions())
            &&  static::class === $configuration->getOptions()['converter'];
    }

}