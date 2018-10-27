<?php
/**
 * Created by PhpStorm.
 * User: Mobiblanc
 * Date: 26/10/2018
 * Time: 16:21
 */

namespace App\Services;


class CitiesApiService extends ApiCustomerService
{

    public function getCities($params)
    {
        //$requestContrat = json_encode($params);
        $response = $this->httpClient->post("listVilles", [
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