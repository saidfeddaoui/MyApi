<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\Contrats;
use App\DTO\Api\ApiResponse;
use App\Services\DocApiService;
use App\Services\UploadDocApiService;
use App\Services\VillesApiService;
use App\Services\ContratApiService;
use App\Services\DetailsContratApiService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Swagger\Annotations as SWG;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Rest\Route(path="/public/document", name="api_document_")
 */
class DocController extends BaseController
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
     * @Rest\Post(path="/list",name="list")
     * @param  Request $request
     * @param DocApiService $docApiService
     * @return JsonResponse
     */
    public function getListDocs(Request $request, DocApiService $docApiService)
    {
        /*$idCompteMobile=$request->request->get('idCompteMobile');
        $idPredeclaration=$request->request->get('idPredeclaration');

        //$params=array("idCompteMobile"=>$idCompteMobile,"idPredeclaration"=>$idPredeclaration);*/
        $params=json_decode($request->getContent());
        $documents = $docApiService->getListDocs($params);
        //var_dump($cities);die;
        return  new JsonResponse($documents, 200);
        //return $this->respondWith($cities);
    }


    /**
     * @Rest\Post(path="/upload",name="upload")
     * @param  Request $request
     * @param UploadDocApiService $uploadDocApiService
     * @return JsonResponse
     */
    public function uploadDocument(Request $request, UploadDocApiService $uploadDocApiService)
    {
        /*$idCompteMobile=$request->request->get('idCompteMobile');
        $idPredeclaration=$request->request->get('idPredeclaration');
        $idTypeDoc=$request->request->get('idTypeDoc');
        $CodeTypeDoc=$request->request->get('CodeTypeDoc');
        $params=array("idCompteMobile"=>$idCompteMobile,"idPredeclaration"=>$idPredeclaration,"idTypeDoc"=>$idTypeDoc,"CodeTypeDoc"=>$CodeTypeDoc);*/
        $params=json_decode($request->getContent());
        //var_dump($params);die;
        $data = $uploadDocApiService->UploadDocs($params);
        if($data){
            $result=array("statut"=>Response::OK,"message"=>"fichier bien charger !!","file"=>$data);
        }else{
            $result=array("statut"=>Response::KO,"message"=>"problème chargement fichier","file"=>"");
        }

        var_dump($result);die;
        return  new JsonResponse($result);
        //return $this->respondWith($cities);
    }








}