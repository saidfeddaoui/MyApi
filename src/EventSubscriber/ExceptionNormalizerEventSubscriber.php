<?php

namespace App\EventSubscriber;

use App\DTO\Api\ApiResponse;
use App\Normalizer\NormalizerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionNormalizerEventSubscriber implements EventSubscriberInterface
{

    const API_FIREWALLS = [
        'security.firewall.map.context.api',
        'security.firewall.map.context.api_public',
    ];
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var array
     */
    private $normalizers = [];

    /**
     * ExceptionNormalizerEventSubscriber constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function processException(GetResponseForExceptionEvent $event)
    {
        return;
        $request = $event->getRequest();
        $firewall = $request->attributes->get('_firewall_context');
        $pathInfo = $request->getPathInfo();
        if (!$firewall && !preg_match('#^/api#', $pathInfo)) {
            return;
        }
        if ($firewall && !in_array($firewall, self::API_FIREWALLS)) {
            return;
        }
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
    /**
     * @param NormalizerInterface $normalizer
     */
    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::EXCEPTION => ['processException', 255] ];
    }

}