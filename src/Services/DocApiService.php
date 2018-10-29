<?php
/**
 * Created by PhpStorm.
 * User: Mobiblanc
 * Date: 26/10/2018
 * Time: 16:21
 */

namespace App\Services;


class DocApiService extends ApiCustomerService
{

    public function getListDocs($params)
    {
        $requestDocs = json_encode($params);
        $response = $this->httpClient->post("", [
            'body' => $requestDocs,
            'headers' => ['Content-type' => 'application/json']
        ]);
        return $this->getResult($response);
    }

    public function UploadDocs($params)
    {
        $requestDocsUpload = json_encode($params);
        $response = $this->httpClient->post("", [
            'body' => $requestDocsUpload,
            'headers' => ['Content-type' => 'application/json']
        ]);
        //var_dump($this->getResult($response));die;
        return $this->getResult($response);
    }

    protected function getResult($response)
    {
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        $data = (string)($response->getBody());
        var_dump($data);die;
        $result=json_decode($data);
        return $result;
    }


}