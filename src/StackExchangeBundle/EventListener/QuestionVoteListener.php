<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 19:04
 */

namespace StackExchangeBundle\EventListener;


use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Event\QuestionVoteEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuestionVoteListener implements EventSubscriberInterface
{
    public function setDefaultScore(QuestionEvent $event)
    {
        $question = $event->getQuestion();

        if ($question->getScore() == null) {
            $question->setScore(0);
        }
    }

    public function updateQuestionScore(QuestionVoteEvent $event)
    {
        $vote = $event->getVote();
        /** @var Question $question */
        $question = $vote->getObject();

        $question->incrementScore($vote->getValue());
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::QUESTION_PRE_PERSIST => 'setDefaultScore',
            Events::QUESTION_VOTE_PRE_PERSIST => 'updateQuestionScore',
        );
    }
}