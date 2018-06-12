<?php

namespace App\EventSubscriber;
/**
 * Created by PhpStorm.
 * User: JIDAL_MOHAMED
 * Date: 11/06/2018
 * Time: 10:26
 */

use App\Entity\Attachment;
use App\Entity\ItemList;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Symfony\Component\HttpFoundation\RequestStack;
class PathSerializerEventSubscriber implements EventSubscriberInterface
{
    protected $requestStack;


    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    /**
     * Returns the events to which this class has subscribed.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize','class' => ItemList::class],
        ];
    }

    public function onPreSerialize(ObjectEvent $event)
    {


        $request = $this->requestStack->getCurrentRequest();
        $dirWeb = $request->getSchemeAndHttpHost();

        if($event->getObject() instanceof ItemList) {
            $object  = $event->getObject();
            foreach ($object->getItems() as $key => $item){
                $img_attachment = new Attachment();
                $icon_attachment = new Attachment();
                if($item->getImage()){
                    $img_attachment->setPath($dirWeb.'/img/'.$item->getImage()->getPath());
                    $item->setImage($img_attachment);
                }
                if($item->getIcon()){
                    $icon_attachment->setPath($dirWeb.'/img/'.$item->getIcon()->getPath());
                    $item->setIcon($icon_attachment);
                }

            }
        }else{
            $object  = $event->getObject();
            $object->setPath( $dirWeb.'/img/'.$object->getPath() );

        }

    }
}