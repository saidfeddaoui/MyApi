<?php

namespace App\Services;

use App\Entity\DevisHabitation;
use JMS\Serializer\SerializationContext;

class DevisHabitationApiService extends ApiCustomerService
{

    public function getDevisHabitation(DevisHabitation $devisHabitation)
    {
        $context = new SerializationContext();
        $requestAuto = $this->serializer->serialize($devisHabitation,'json',$context);
        $response = $this->httpClient->post("habitation", [
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