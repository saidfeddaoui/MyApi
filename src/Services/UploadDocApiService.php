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
        $requestDoc = json_encode($params);
        $response = $this->httpClient->post("", [
            'body' => $requestDoc,
            'headers' => ['Content-type' => 'application/json']
        ]);
        //var_dump($response->getBody())
        return $this->getResult($response);
    }

    protected function getResult($response)
    {
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        $data = (string)($response->getBody());
        //var_dump($data);die;
        $result=json_decode($data);
        return $result;
    }


}