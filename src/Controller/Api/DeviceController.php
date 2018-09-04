<?php

namespace App\Controller\Api;


use App\Entity\Device;
use App\Services\ApiDevices;
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
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
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
     * @param Device $device
     * @return ApiResponse
     */
    public function addDeviceAction(Request $request,ApiDevices $apiDevices)
    {
        // check Method
        $method = $this->container->get('app.check.params')->checkMethod($request,'POST');
        if ($method['status'] == 'OK'){
            // Params
            $device_uid = $request->request->get('device_uid');
            $os = strtolower($request->request->get('os'));
            $model = $request->request->get('model');
            $version_code = $request->request->get('version_code');
            $firebase_token = $request->request->get('firebase_token');
            $pushable = $request->request->get('pushable');
            $latitude = $request->request->get('latitude');
            $longitude = $request->request->get('longitude');
            $device = array(
                'device_uid'=> $device_uid,
                'os'=> $os,
                'model'=> $model,
                'firebase_token'=>$firebase_token,
                'version_code'=> $version_code,
                'pushable'=> $pushable,
                'latitude'=> $latitude,
                'longitude'=> $longitude,
            );

            $status = $this->container->get('app.check.params')->checkParams($device);
            if($status['status'] ==  "OK"){
                $dateTime=new \DateTime('now');
                $dateTime->format('Y-m-d H:i:s');
                $apiDevices->setDevice($device,$dateTime);
                $check_version = $apiDevices->checkVersion($os, $version_code);
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
