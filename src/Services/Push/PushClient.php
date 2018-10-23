<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 06/06/2018
 * Time: 12:55
 */

namespace App\Services\Push;
use App\Services\Push\ConfigTrait;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class PushClient
{
    use ConfigTrait;

    public function sendPush($push,$tokens)
    {
        $success = 0;
        $failure = 0;

        if ($push['canal'] == 'mcma'){
            $cle_serveur_firebase = $this->cle_serveur_firebase_mcma;
        }else{
            $cle_serveur_firebase = $this->cle_serveur_firebase_mamda;
        }
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $cle_serveur_firebase
        );


        $iterations = ceil(count($tokens) / $this->nbre_device_one_send);
        for ($i = 0; $i < $iterations; $i++) {
            $offset = $i * $this->nbre_device_one_send;
            $batch_of_1000_token = array_slice($tokens, $offset, $this->nbre_device_one_send);
            $messageJson = $this->getPayload($push['title'], $push['message'], isset($push['id'])?$push['id']:'0', $batch_of_1000_token);
            /**
             *
             * Envoi pour un seul paquet de device (max = 1000)
             */
            $retour = $this->send_push_one($messageJson, $headers);
            $success = $success + $retour["success"];
            $failure = $failure + $retour["failure"];

        }

        $array = array("success" => $success, "failure" => $failure);
        return $array;

    }


    public function getPayload($title, $message, $id, $reg)
    {
        $notification = array(
            'title' => $title,
            'body' => $message,

            "timestamp"=>date('Y-m-d G:i:s')
        );

        $fields = array(
            'registration_ids' => $reg,
            'notification' => $notification,
            "priority" => "high",
            "badge" => 1,
            "time_to_live" => $this->time_to_live,
            'content_available' => true,
            "is_background"=>false,
            "timestamp"=>date('Y-m-d G:i:s'),
            'sound' => "Default",
            'data' => array(
                'id' => $id,
                'title' => $title,
                'body' => $message,
            )
        );
        return json_encode($fields);
    }

    public function send_push_one($messageJson, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->url_firebase,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $messageJson
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $obj = json_decode($response);
        if (isset($obj->{'success'})) {
            $array = array("success" => $obj->{'success'}, "failure" => ($obj->{'failure'}));
            return $array;
        } else {
            return true;
        }

    }


}