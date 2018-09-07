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
    private $cle_serveur_firebase = "AAAAaACwVCM:APA91bH6UebnvGl6fdU-xrOE0hhH8ulQ_WQ3hleEDLkQL1jOOYod6dDQCZweIzM-6i1pWciL8W3myl7BkAAX3Xza5rH-EzddM4eyqhbqMqjZyCP39qZRY07X92VQM_3YOpbgxn17Yhs1C-lBLTciu1pFpU7L4kPqpw";
    private $nbre_device_one_send = 999;
    private $url_firebase = "https://fcm.googleapis.com/fcm/send";
    private $time_to_live = 1800;

}