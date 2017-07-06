<?php

namespace CarShowBundle\EventListener;

use CarShowBundle\Entity\Car;
use CarShowBundle\Event\CarEvent;
use CarShowBundle\Event\PostEvent;
use CarShowBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CarUpdatedListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::CAR_POST_PERSIST => 'setUpdatedAt',
            Events::POST_PRE_PERSIST => 'setCarUpdatedFromPost',
            Events::POST_DELETE => 'setCarUpdatedFromPost',
            Events::CAR_PRE_PERSIST => 'setMandatoryInfo'
        );
    }

    public function setUpdatedAt(CarEvent $event)
    {
        /** @var Car $car */
        $car = $event->getCar();

        $car->setUpdatedAt(new \DateTime('now'));
    }

    public function setCarUpdatedFromPost(PostEvent $event)
    {
        $post = $event->getPost();
        $car = $post->getAuto();
        $car->setUpdatedAt(new \DateTime('now'));
    }

    public function setMandatoryInfo(CarEvent $event)
    {
        /** @var Car $car */
        $car = $event->getCar();

        if (null == $car->getDeleted()) {
            $car->setDeleted(false);
        }

        if (null == $car->getCreatedAt()) {
            $car->setCreatedAt(new \DateTime('now'));
        }

        if (null == $car->getUpdatedAt()) {
            $car->setUpdatedAt(new \DateTime('now'));
        }
    }
}