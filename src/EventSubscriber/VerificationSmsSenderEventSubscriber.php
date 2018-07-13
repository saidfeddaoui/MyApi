<?php

namespace App\EventSubscriber;

use App\Event\ApplicationEvents;
use App\Event\PhoneRegistrationEvent;
use App\Services\SmsApiService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VerificationSmsSenderEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var SmsApiService
     */
    private $sender;

    /**
     * VerificationSmsSenderEventSubscriber constructor.
     * @param SmsApiService $sender
     */
    public function __construct(SmsApiService $sender)
    {
        $this->sender = $sender;
    }

    public function send(PhoneRegistrationEvent $event)
    {
        $client = $event->getClient();
        $this->sender->sendSms($client->getPhone(), $client->getVerificationCode());
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [ApplicationEvents::PHONE_REGISTRATION => 'send'];
    }

}