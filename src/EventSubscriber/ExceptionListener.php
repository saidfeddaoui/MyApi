<?php
/**
 * Created by PhpStorm.
 * User: JIDAL_MOHAMED
 * Date: 29/06/2018
 * Time: 14:59
 */

namespace App\EventSubscriber;

use App\DTO\Api\ApiResponse;
use App\Normalizer\NormalizerInterface;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class ExceptionListener implements EventSubscriberInterface
{

    private $serializer;
    private $normalizers = [];

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function processException(GetResponseForExceptionEvent $event)
    {

        $result = null;
        $response = new ApiResponse([], ApiResponse::INTERNAL_SERVER_ERROR);
        $exception = $event->getException();
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($exception)) {
                $response = $normalizer->normalize($exception);
                break;
            }
        }
        $body = $this->serializer->serialize($response, 'json');
        $event->setResponse(new Response($body, $response->getHttpStatusCode()));
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