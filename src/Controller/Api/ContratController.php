<?php

namespace App\Controller\Api;

use App\Entity\Contrats;
use App\DTO\Api\ApiResponse;
use App\Services\ContratApiService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse ;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;

/**
 * @Rest\Route(path="/public/contrat", name="api_contrat_")
 */
class ContratController extends BaseController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @Rest\Post(path="/add",name="add")
     * @param  Request $contrat
     * @param ContratApiService $contratApiService
     * @return JsonResponse
     */
    public function AddContrat(Request $request, ContratApiService $contratApiService)
    {
        $contrat=json_decode($request->getContent());
        $contra = $contratApiService->AddContrat($contrat);
        return  new JsonResponse($contra, 200);
       // return $this->respondWith($contrat);
    }



    /**
     *
     *
     * @Rest\Get(path = "/{id}", name = "contrat")
     * @Rest\View(serializerGroups={"all", "contrats"})
     *
     * @param integer $id
     * @return ApiResponse
     *
     */
    public function getContrat($id)
    {

        $contrat = $this->em->getRepository('App:Contrats')->find($id);

       // return $this->respondWith($contrat);
    }


}