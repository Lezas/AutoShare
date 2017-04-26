<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 18:55
 */

namespace StackExchangeBundle\EventListener;


use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuestionDeleteListener implements EventSubscriberInterface
{

    public function setDeleted(QuestionEvent $event)
    {

        $question = $event->getQuestion();



        if ($question->isDeleted() == null) {
            $question->setDeleted(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(Events::QUESTION_PRE_PERSIST => 'setDeleted');
    }
}