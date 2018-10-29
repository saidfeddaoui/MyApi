<?php
/**
 * Created by PhpStorm.
 * User: mobiblanc
 * Date: 08/10/2018
 * Time: 16:39
 */

namespace App\Controller\Api;

use App\Entity\ChoixMDR;
use App\Entity\PreDeclaration;
use App\DTO\Api\ApiResponse;
use App\Services\MDRApiService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Route(path="/public", name="api_modeReparation_")
 */
class ModeReparationController extends BaseController
{


    //liste Notification

    /**
     * @SWG\Get(
     *     tags={"ModesReparation"},
     *     description="Modes Reparation Actifs",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return Modes Reparation",
     *     )
     * )
     *
     * @Rest\Get(path="/get/modesReparationActifs/{id_predeclaration}", name="modesReparationsActifs")
     * @Rest\View()
     * @param $id_predeclaration
     * @param ObjectManager $em
     * @return JsonResponse
     */
    public function detailsNotification(ObjectManager $em,  $id_predeclaration)
    {
        $preDeclaration = $em->getRepository("App:PreDeclaration")->findOneBy(array("id"=>$id_predeclaration));
        $listModesReparation=array();
        if($preDeclaration instanceof PreDeclaration){
            $modesReparations=$preDeclaration->getPreDeclaration_modeReparation_associations();
            if(!count($modesReparations)){
                $response['header'] = array('status'=>Response::HTTP_OK,'message'=>'toutes les modes de réparation sont inactifs !!');
                $response['results'] = array();
                return new JsonResponse($response);
            }
            foreach ($modesReparations as $mode){
                if($mode instanceof ChoixMDR){
                    if($mode->getStatut()==true){
                        array_push(
                            $listModesReparation,
                            array(
                                'id_modes_reparation' => $mode->getModeReparation()->getId(),
                                'description_modes_reparation' => $mode->getModeReparation()->getDescription(),
                                'code_modes_reparation'=> $mode->getModeReparation()->getCode()
                            )
                        );
                    }
                }
            }
            $response['header'] = array('status'=>Response::HTTP_OK,'message'=>'');
            $response['results'] = $listModesReparation;
            return new JsonResponse($response);
        }else{
            $response['header'] = array('status'=>Response::HTTP_NOT_FOUND,'message'=>'');
            $response['results'] = array();
            return new JsonResponse($response);
        }

    }


    /**
     * @Rest\Post(path="/envoi_MDR",name="send")
     * @param  Request $request
     * @param MDRApiService $mdrApiService
     * @return JsonResponse
     */
    public function envoiMDR(Request $request, MDRApiService $MDRApiService)
    {
        $idCompteMobile=$request->request->get('idCompteMobile');
        $idPredeclaration=$request->request->get('idPredeclaration');
        $idModeReparation=$request->request->get('idModeReparation');
        $CodeModeReparation=$request->request->get('CodeModeReparation');
        $idVille=$request->request->get('idVille');
        $idGarage=$request->request->get('idGarage');
        $commentaire=$request->request->get('commentaire');

        //$params=array(
        //"idCompteMobile"=>$idCompteMobile,
        //"idPredeclaration"=>$idPredeclaration,
        //"idModeReparation"=>$idModeReparation,
        //"CodeModeReparation"=>$CodeModeReparation,
        //"idVille"=>$idVille,
        //"idGarage"=>$idGarage,
        //"commentaire"=>$commentaire,
        //);
        $params=$request->getContent();
        $data = $MDRApiService->sendMDR($params);
        //var_dump($cities);die;
        return  new JsonResponse($data, 200);
        //return $this->respondWith($cities);
    }

}