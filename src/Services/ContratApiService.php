<?php

namespace App\Services;


use JMS\Serializer\SerializationContext;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContratApiService extends ApiCustomerService
{

    public function AddContrat($contrat)
    {
        $requestContrat = json_encode($contrat);
        $response = $this->httpClient->post("", [
            'body' => $requestContrat,
            'headers' => ['Content-type' => 'application/json']
        ]);
        return $this->getResult($response);
    }


    public function detailsContrat($contrat)
    {

        $requestContrat = json_encode($contrat);
        $response = $this->httpClient->post("", [
            'body' => $requestContrat,
            'headers' => ['Content-type' => 'application/json']
        ]);
        return $this->getResult($response);
    }


    /**
     * @param $response
     * @return JsonResponse
     */
    protected function getResult($response)
    {
        if (200 !== $response->getStatusCode()) {
            return null;
        }

       $result = (string) $response->getBody();
        $autoResponse = json_decode($result);
        return $autoResponse;
    }

}