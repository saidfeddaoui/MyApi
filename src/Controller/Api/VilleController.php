<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\Contrats;
use App\DTO\Api\ApiResponse;
use App\Services\VillesApiService;
use App\Services\ContratApiService;
use App\Services\DetailsContratApiService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse ;
use Symfony\Component\HttpFoundation\Request;

use Swagger\Annotations as SWG;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Rest\Route(path="/public/villes", name="api_")
 */
class VilleController extends BaseController
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
     * @Rest\Post(path="/list",name="list_villes")
     * @param  Request $request
     * @param VillesApiService $citiesApiService
     * @return JsonResponse
     */
    public function getCities(Request $request, VillesApiService $citiesApiService)
    {
        /*$idCompteMobile=$request->request->get('idCompteMobile');
        $idPredeclaration=$request->request->get('idPredeclaration');
        $idModeReparation=$request->request->get('idModeReparation');
        $CodeModeReparation=$request->request->get('CodeModeReparation');

        //$params=array("idCompteMobile"=>$idCompteMobile,"idPredeclaration"=>$idPredeclaration,"idModeReparation"=>$idModeReparation,"CodeModeReparation"=>$CodeModeReparation);*/
        $params=json_decode($request->getContent());
        $cities = $citiesApiService->getCities($params);
        //var_dump($cities);die;
        return  new JsonResponse($cities, 200);
        //return $this->respondWith($cities);
    }








}