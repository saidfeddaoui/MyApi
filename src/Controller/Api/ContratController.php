<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\Contrats;
use App\DTO\Api\ApiResponse;
use App\Services\ContratApiService;
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
     * @param  Request $request
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
     * @Rest\Post(path="/details",name="details")
     * @param  Request $request
     * @param ContratApiService $contratApiService
     * @return JsonResponse
     */
    public function detailsContrat(Request $request, ContratApiService $contratApiService)
    {
        $contrat=json_decode($request->getContent());
        $contra = $contratApiService->detailsContrat($contrat);
        return  new JsonResponse($contra, 200);
    }


    /**
     * @Rest\Get(path = "/{id}", name = "contrat")
     * @Rest\View(serializerGroups={"all", "contrats"})
     * @param Client $id
     * @return ApiResponse
     */
    public function getContrat($id)
    {
        $contrat = $this->em->getRepository('App:Contrats')->findByClient($id);
        return $this->respondWith($contrat);
    }


    /**
     * @Rest\Delete(path = "/delete/{numeroContrat}", name = "delete")
     * @Rest\View(serializerGroups={"all", "contrats"})
     * @param Contrats $contrat
     * @return JsonResponse
     */
    public function deleteContrat(Contrats $contrat)
    {
        $this->em->remove($contrat);
        $this->em->flush();
        return new JsonResponse(array(
            "code"=>200,
            "status"=>"ok",
            "message"=>"le contrat à été supprimé avec succès"
        ));
    }





}