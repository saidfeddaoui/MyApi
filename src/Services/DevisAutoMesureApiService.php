<?php

namespace App\Services;


use App\DTO\Api\Devis\Mesure;
use JMS\Serializer\SerializationContext;

class DevisAutoMesureApiService extends ApiCustomerService
{

    public function getDevisAuto(Mesure $mesure)
    {
        $context = new SerializationContext();
        $requestAuto = $this->serializer->serialize($mesure,'json',$context);
        $response = $this->httpClient->post("mesure", [
            'body' => $requestAuto,
            'headers' => ['Content-type' => 'application/json']
        ]);
        return $this->getResult($response);
    }

    /**
     * @param $response
     * @return null
     */
    protected function getResult($response)
    {
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        /**
         * @var \App\DTO\Devis\AutoMesureResponse $autoMesureResponse
         */
        $autoMesureResponse = $this->serializer->deserialize( (string) $response->getBody(), $this->class, 'json');
        return $autoMesureResponse;
    }

}