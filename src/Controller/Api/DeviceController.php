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
        $method = $validator->checkMethod('POST');
        if ($method['status'] == 'OK'){
            // Params
            $device_uid = $request->request->get('device_uid');
            $os = strtolower($request->request->get('os'));
            $model = $request->request->get('model');
            $version_name = $request->request->get('version_name');
            $version_code = $request->request->get('version_code');
            $firebase_token = $request->request->get('firebase_token');
            $pushable = $request->request->get('pushable');
            $latitude = $request->request->get('latitude');
            $longitude = $request->request->get('longitude');
            $canal = strtolower($request->request->get('canal'))?:'mamda';
            $device = array(
                'device_uid'=> $device_uid,
                'os'=> $os,
                'model'=> $model,
                'version_name'=> $version_name,
                'version_code'=> $version_code,
                'firebase_token'=> $firebase_token,
                'pushable'=> $pushable,
                'latitude'=> $latitude,
                'longitude'=> $longitude,
                'canal'=> $canal,
            );

            $status = $validator->checkParams($device);
            if($status['status'] ==  "OK"){
                $dateTime=new \DateTime('now');
                $dateTime->format('Y-m-d H:i:s');
                $apiDevices->setDevice($device,$dateTime);
                $check_version = $apiDevices->checkVersion($os, $version_name);
                $response['header'] = $check_version;
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
