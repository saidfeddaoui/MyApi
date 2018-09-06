<?php

namespace App\Controller\Api;


use App\Services\ApiDevices;
use App\Services\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Rest\Route(path="/public/device", name="api_device_")
 */
class DeviceController extends Controller
{
    /**
     * @SWG\Post(
     *     tags={"Devices"},
     *     description="AddDevice Process",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Parameter(
     *        name="Device",
     *        in="body",
     *        description="Device object",
     *        required=true,
     *        @Model(type="App\Entity\Device", groups={"all"})
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Failure response",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Validation Error (Http Code: 406)":
     *             {
     *                 "code"=406,
     *                 "status"="Constraint Violation Error"
     *             },
     *             "Not Found Error (Http Code: 404)":
     *             {
     *                 "code"=404,
     *                 "status"="Resource Not Found"
     *             }
     *         }
     *     )
     * )
     *
     * @Rest\Post(path="/add",name="add")
     * @Rest\View(serializerGroups={"all"})
     *
     * @param ApiDevices $apiDevices
     * @param Request $request
     * @param Validator $validator
     * @return JsonResponse
     */
    public function addDeviceAction(Request $request,ApiDevices $apiDevices, Validator $validator)
    {
        // check Method
        dump($request->getMethod());
        die();
        $method = $validator->checkMethod($request,'POST');
        if ($method['status'] == 'OK'){
            // Params
            $device_uid = $request->request->get('device_uid');
            $os = strtolower($request->request->get('os'));
            $model = $request->request->get('model');
            $version_name = $request->request->get('version_name');
            $version_code = $request->request->get('version_code');
            $firebase_token = $request->request->get('firebase_token');
            $fuseau_horaire = $request->request->get('fuseau_horaire');
            $resolution = $request->request->get('resolution'); // 480*800
            $pushable = $request->request->get('pushable');
            $langue = $request->request->get('langue');
            $latitude = $request->request->get('latitude');
            $longtitude = $request->request->get('longtitude');
            $device = array(
                'device_uid'=> $device_uid,
                'os'=> $os,
                'model'=> $model,
                'version_name'=> $version_name,
                'version_code'=> $version_code,
                'firebase_token'=> $firebase_token,
                'fuseau_horaire'=> $fuseau_horaire,
                'resolution'=> $resolution,
                'pushable'=> $pushable,
                'langue'=> $langue,
                'latitude'=> $latitude,
                'longtitude'=> $longtitude,
            );

            $status = $validator->checkParams($device);
            if($status['status'] ==  "OK"){
                $dateTime=new \DateTime('now');
                $dateTime->format('Y-m-d H:i:s');
                dump($device);
                die();
                $apiDevices->setDevice($device,$dateTime);
                //$check_version = $apiDevices->checkVersion($os, $version_code);
                $response['header'] = array('status'=>'OK','message'=>'SUCCESSFULY UPDATED');;
                $response['results'] =  array();
                return new JsonResponse($response);

            }else{
                $response['header'] = $status;
                $response['results'] = array();
                return new JsonResponse($response);
            }
        }else{
            $response['header'] = $method;
            $response['results'] = array();
            return new JsonResponse($response);
        }
    }



}
