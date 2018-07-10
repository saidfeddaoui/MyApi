<?php

namespace App\EventSubscriber;

use App\DTO\Api\ApiResponse;
use App\Normalizer\NormalizerInterface;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{

    const API_FIREWALLS = [
        'security.firewall.map.context.api',
        'security.firewall.map.context.api_public',
    ];

    private $serializer;
    private $normalizers = [];

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        $firewall = $event->getRequest()->attributes->get('_firewall_context');
        if (!in_array($firewall, self::API_FIREWALLS)) {
            return;
        }
        $result = null;
        $exception = $event->getException();
        $response = new ApiResponse([], ApiResponse::INTERNAL_SERVER_ERROR);
        if ($message = $exception->getMessage()) {
            $response->setStatus($message);
        }
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($exception)) {
                $response = $normalizer->normalize($exception);
                break;
            }
        }
        $body = $this->serializer->serialize($response, 'json');
        $event->setResponse(new JsonResponse($body, $response->getHttpStatusCode(), [], true));
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 255]]
        ];
    }

}