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
        $devices = $this->em->getRepository(Device::class)->findOneBy(array('device_uid'=>$device_params['device_uid'],'canal'=>$device_params['canal']));
        dump($devices->getCanal());die;
        if (!$devices instanceof Device){
            $devices = new Device();
            $devices->setDeviceUid($device_params['device_uid']);
            $devices->setOs($device_params['os']);
            $devices->setModel($device_params['model']);
            $devices->setCreatedAt($dateTime);
        }
        $devices->setVersionName($device_params['version_name']);
        $devices->setCanal($device_params['canal']);
        $devices->setVersionCode($device_params['version_code']);
        $devices->setFirebaseToken($device_params['firebase_token']?:'');
        $devices->setPushable($device_params['pushable']);
        $devices->setLatitude($device_params['latitude']);
        $devices->setLongitude($device_params['longitude']);
        $devices->setUpdatedAt($dateTime);
        $this->em->persist($devices);
        $this->em->flush();
        return true;
    }



}