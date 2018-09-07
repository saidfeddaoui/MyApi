<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 03/07/2018
 * Time: 12:23
 */

namespace App\Services\Push;


trait ConfigTrait
{
    private $cle_serveur_firebase = "AAAAia9jv0U:APA91bENN1DNtWst7QEp9ZMo22dLdp-noNrjiMkGz2_x9wyR-ceNIk7GVX3dIB33IgPSVwfya0ISqNCbKGKcpICVrqR-d69NHt-rx5Zz1DKh6MZkKBY0oBaUk_aD_wPBBFPR2fhcunnm";
    private $nbre_device_one_send = 999;
    private $url_firebase = "https://fcm.googleapis.com/fcm/send";
    private $time_to_live = 1800;

}