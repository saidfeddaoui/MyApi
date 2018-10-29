<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\Contrats;
use App\DTO\Api\ApiResponse;
use App\Services\GaragesApiService;
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
 * @Rest\Route(path="/public/garages", name="api_")
 */
class GarageController extends BaseController
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
     * @Rest\Post(path="/list",name="list_garages")
     * @param  Request $request
     * @param GaragesApiService $garagesApiService
     * @return JsonResponse
     */
    public function getGarages(Request $request, GaragesApiService $garagesApiService)
    {
        /*$idCompteMobile=$request->request->get('idCompteMobile');
        $idPredeclaration=$request->request->get('idPredeclaration');
        $idModeReparation=$request->request->get('idModeReparation');
        $CodeModeReparation=$request->request->get('CodeModeReparation');
        $idVille=$request->request->get('idVille');
        //$params=array("idCompteMobile"=>$idCompteMobile,"idPredeclaration"=>$idPredeclaration,"idModeReparation"=>$idModeReparation,"CodeModeReparation"=>$CodeModeReparation,"idVille"=>$idVille);
        */
        $params=json_decode($request->getContent());
        $garages = $garagesApiService->getGarages($params);
        //var_dump($cities);die;
        return  new JsonResponse($garages, 200);
        //return $this->respondWith($cities);
    }








}