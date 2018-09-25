<?php

namespace App\Services;


use JMS\Serializer\SerializationContext;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class PreDeclarationTriggerApiService extends ApiCustomerService
{

    public function triggerPredeclaration($predeclaration)
    {
        $requestPredeclaration = json_encode($predeclaration);
        $response = $this->httpClient->post("", [
            'body' => $requestPredeclaration,
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