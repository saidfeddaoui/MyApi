<?php

namespace App\EventSubscriber;

use App\Entity\Attachment;
use App\Entity\ItemList;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class PathSerializerEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * PathSerializerEventSubscriber constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPreSerialize(ObjectEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $pathToImageDir = $request->getSchemeAndHttpHost() . '/img/';
        $object  = $event->getObject();
        if ($object instanceof ItemList) {
            foreach ($object->getItems() as $key => $item) {
                if ($item->getImage()) {
                    $item->setImage(new Attachment($pathToImageDir . $item->getImage()->getPath()));
                }
                if ($item->getIcon()) {
                    $item->setIcon(new Attachment($pathToImageDir . $item->getIcon()->getPath()));
                }
            }
        }
    }
    /**
     * Returns the events to which this class has subscribed.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize', 'class' => ItemList::class],
        ];
    }

}