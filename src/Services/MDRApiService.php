<?php
/**
 * Created by PhpStorm.
 * User: Mobiblanc
 * Date: 26/10/2018
 * Time: 16:21
 */

namespace App\Services;


class MDRApiService extends ApiCustomerService
{

    public function sendMDR($params)
    {
        //$requestContrat = json_encode($params);
        $response = $this->httpClient->post("", [
            'form_params' => [$params]
        ]);
        return $this->getResult($response);
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