<?php

namespace StackExchangeBundle\EventListener;

use StackExchangeBundle\Event\AnswerEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AnswerAcceptedListener implements EventSubscriberInterface
{
    public function setAnswerAccepted(AnswerEvent $event)
    {
        $answer = $event->getAnswer();

        if ($answer->isAccepted() == false) {
            $answer->setAccepted(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::ANSWER_ACCEPTED=> 'setAnswerAccepted',
        );
    }
}