<?php
/**
 * Created by PhpStorm.
 * User: gbtux
 * Date: 29/11/17
 * Time: 19:34
 */

namespace App\EventSubscriber;


use \Hashids\Hashids;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class HashSuscriber implements EventSubscriberInterface
{
    /**
     * @var Hashids
     */
    private $hashids;

    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }


    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerializeData',
                'class' => 'App\Entity\Document'
            ],
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerializeData',
                'class' => 'App\Entity\Partage'
            ],
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerializeData',
                'class' => 'App\Entity\DocEvenement'
            ],
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerializeData',
                'class' => 'App\Entity\Utilisateur'
            ],
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerializeData',
                'class' => 'App\Entity\Groupe'
            ]
        ];
    }

    public function onPreSerializeData(PreSerializeEvent $event)
    {
        $object = $event->getObject();
        $object->setHashedId($this->hashids->encode($object->getId()));
    }

}