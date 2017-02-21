<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 18:55
 */

namespace StackExchangeBundle\EventListener;

use StackExchangeBundle\Event\AnswerEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AnswerDeleteListener implements EventSubscriberInterface
{

    /**
     * @param AnswerEvent $event
     */
    public function setDeleted(AnswerEvent $event)
    {
        $answer = $event->getAnswer();

        if ($answer->isDeleted() == null) {
            $answer->setDeleted(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(Events::ANSWER_PRE_PERSIST => 'setDeleted');
    }
}