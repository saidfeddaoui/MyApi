<?php

namespace App\EventSubscriber;

use App\Entity\Client;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LocaleEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var TranslatableListener
     */
    private $translatableListener;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * LocaleListener constructor.
     * @param TranslatableListener $translatableListener
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TranslatableListener $translatableListener, TokenStorageInterface $tokenStorage)
    {
        $this->translatableListener = $translatableListener;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($locale = $request->query->get('lang')) {
            $request->setLocale($locale);
        }
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        if ($user && $user instanceof Client) {
            $request->setLocale($user->getLang());
        }
        $this->translatableListener->setTranslatableLocale($request->getLocale());
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

}