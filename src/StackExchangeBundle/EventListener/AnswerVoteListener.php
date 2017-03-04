<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 19:04
 */

namespace StackExchangeBundle\EventListener;

use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Event\AnswerEvent;
use StackExchangeBundle\Event\AnswerVoteEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AnswerVoteListener implements EventSubscriberInterface
{
    /**
     * @param AnswerEvent $event
     */
    public function setDefaultScore(AnswerEvent $event)
    {
        $answer = $event->getAnswer();

        if ($answer->getScore() == null) {
            $answer->setScore(0);
        }
    }

    public function updateAnswerScore(AnswerVoteEvent $event)
    {
        $vote = $event->getVote();
        /** @var Answer $answer */
        $answer = $vote->getObject();

        $answer->incrementScore($vote->getValue());
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::ANSWER_PRE_PERSIST => 'setDefaultScore',
            Events::ANSWER_VOTE_PRE_PERSIST => 'updateAnswerScore',
        );

    }
}