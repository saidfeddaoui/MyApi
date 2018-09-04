<?php

namespace App\Controller\Api;

use App\Services\ApiDevice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeviceController extends Controller
{

    public function addDeviceAction(Request $request,ApiDevices $apiDevices)
    {
        // check Method
        $method = $this->container->get('app.check.params')->checkMethod($request,'POST');
        if ($method['status'] == 'OK'){
            // Params
            $device_uid = $request->request->get('device_uid');
            $os = strtolower($request->request->get('os'));
            $model = $request->request->get('model');
            $version_name = $request->request->get('version_name');
            $version_code = $request->request->get('version_code');
            $firebase_token = $request->request->get('firebase_token');
            $resolution = $request->request->get('resolution'); // 480*800
            $density = $request->request->get('density'); // 240dpi
            $pushable = $request->request->get('pushable');
            $latitude = $request->request->get('latitude');
            $longtitude = $request->request->get('longtitude');
            $device = array(
                'device_uid'=> $device_uid,
                'os'=> $os,
                'model'=> $model,
                'version_name'=> $version_name,
                'version_code'=> $version_code,
                'firebase_token'=> $firebase_token,
                'resolution'=> $resolution,
                'density'=> $density,
                'pushable'=> $pushable,
                'latitude'=> $latitude,
                'longtitude'=> $longtitude,
            );

            $status = $this->container->get('app.check.params')->checkParams($device);
            if($status['status'] ==  "OK"){
                $dateTime=new \DateTime('now');
                $dateTime->format('Y-m-d H:i:s');
                $apiDevices->setDevice($device,$dateTime);
                $check_version = $apiDevices->checkVersion($os, $version_name);
                $response['header'] = $check_version;
                $response['results'] =  array();
                $response['interstitiel'] =  array();
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
