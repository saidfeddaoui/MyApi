<?php

namespace App\Services;

use App\Entity\DevisAuto;
use JMS\Serializer\SerializationContext;

class DevisAutoApiService extends ApiCustomerService
{

    public function getDevisAuto(DevisAuto $devisAuto)
    {
        $context = new SerializationContext();
        $context = $context->setGroups("request_auto");
        $requestAuto = $this->serializer->serialize($devisAuto,'json',$context);
        $response = $this->httpClient->post("devisoptions", [
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
         * @var \App\DTO\Devis\AutoResponse $autoResponse
         */
        $autoResponse = $this->serializer->deserialize( (string) $response->getBody(), $this->class, 'json');
        return $autoResponse;
    }

}