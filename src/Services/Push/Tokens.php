<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 03/07/2018
 * Time: 12:26
 */

namespace App\Services\Push;
use App\Entity\Client;
use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
class Tokens
{

    private $entitymanager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;

    }

    public function getTokenByClient($phone){
        $client = $this->entitymanager ->getRepository(Client::class)->findOneBy(array('phone'=>$phone));
        if ($client instanceof Client){
            $device = $client->getDevice();
            if($device instanceof Device){
                return $device->getFirebaseToken();
            }
        }
        return '';
    }


}