<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 03/07/2018
 * Time: 12:26
 */

namespace App\Services\Push;
use App\Entity\Device;

class Tokens
{
    public function getTokenByClient($device){
            if($device instanceof Device){
                return $device->getFirebaseToken();
            }
        return '';
    }


}