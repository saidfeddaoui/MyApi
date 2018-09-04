<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 17/05/2018
 * Time: 16:12
 */
namespace App\Services;

use App\Entity\Device;
use App\Entity\Version;

use Doctrine\ORM\EntityManagerInterface;

class ApiDevices
{
    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }

    // Device entity
    public function setDevice($device_params,$dateTime)
    {
        $devices = $this->em->getRepository(Device::class)->findOneBy(array('device_uid'=>$device_params['device_uid']));
        if ($devices instanceof Device){
            $devices = new Device();
            $devices->setDeviceUid($device_params['device_uid']);
            $devices->setOs($device_params['os']);
            $devices->setModel($device_params['model']);
            $devices->setCreatedAt($dateTime);
            $devices->setVersionName($device_params['version_name']);
            $devices->setVersionCode($device_params['version_code']);
            $devices->setFirebaseToken($device_params['firebase_token']?:'');
            $devices->setResolution($device_params['resolution']);
            $devices->setDensity($device_params['density']);
            $devices->setPushable($device_params['pushable']);
            $devices->setLatitude($device_params['latitude']);
            $devices->setLongitude($device_params['longitude']);
            $devices->setUpdatedAt($dateTime);
            $this->em->persist($devices);
            $this->em->flush();
        }else{
            $devices->setVersionName($device_params['version_name']);
            $devices->setVersionCode($device_params['version_code']);
            $devices->setFirebaseToken($device_params['firebase_token']?:'');
            $devices->setResolution($device_params['resolution']);
            $devices->setDensity($device_params['density']);
            $devices->setPushable($device_params['pushable']);
            $devices->setLatitude($device_params['latitude']);
            $devices->setLongitude($device_params['longitude']);
            $devices->setUpdatedAt($dateTime);
            $this->em->flush();
        }

    }

    public function checkVersion($os, $version_name){
       // $os = $this->em->getRepository(OperatingSystem::class)->findOneBy(array('name' => strtolower($os)));
        $versions = $this->em->getRepository(Version::class)->findOneBy(array('os'=>$os));

        if ($versions instanceof Version){
            if (version_compare($version_name, $versions->getMinVersion()) <= 0){
                return array(
                    'status' => "ERROR",
                    'message' => $versions->getMsgBloquer()?:'',
                    'url' => $versions->getPath()?:'',
                    'cache' => $versions->getCache()?true:false,
                );
            }

            if ((version_compare($version_name, $versions->getMinVersion()) > 0) && (version_compare($version_name, $versions->getCurrentVersion()) < 0)) {
               return array(
                    'status' => "WARNING",
                    'message' => $versions->getMsgInfo(),
                    'url' => $versions->getPath()?:'',
                    'cache' => $versions->getCache()?true:false,
               );
            }
        }

        return array('status'=>'OK','message'=>'SUCCESSFULY UPDATED');

    }



}