<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 03/07/2018
 * Time: 12:26
 */

namespace App\Service\Push;
use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
class Tokens
{

    private $entitymanager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;

    }

    public function getTokensByClient($client){
        $device = $this->entitymanager ->getRepository(Device::class)->findOneBy(array('client'=>$client));
        if($device instanceof Device){
            return $device->getFirebaseToken();
        }
        return '';
    }


}