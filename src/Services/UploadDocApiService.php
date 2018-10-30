<?php
/**
 * Created by PhpStorm.
 * User: Mobiblanc
 * Date: 26/10/2018
 * Time: 16:21
 */

namespace App\Services;


class UploadDocApiService extends ApiCustomerService
{


    public function UploadDocs($params)
    {
        /*£$requestDoc = json_encode($params);
        $response = $this->httpClient->post("", [
            'body' => $requestDoc,
            'headers' => ['Content-type' => 'application/json']
        ]);
        //var_dump($response->getBody());die;
        return $this->getResult($response);*/


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ais.mamda-mcma.ma/WS/api/mode/reparation/getdoc",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 6fa3d47e-df1f-b794-fac8-b5e40d2f99ca"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null;
        } else {
            return $response;
        }
    }

    protected function getResult($response)
    {
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        $data = (string)($response->getBody());
        $result=json_decode($data);
        return $result;
    }


}