<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 19:04
 */

namespace StackExchangeBundle\EventListener;


use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Event\QuestionCommentEvent;
use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Event\QuestionVoteEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuestionCommentListener implements EventSubscriberInterface
{
    public function setDefaultValues(QuestionCommentEvent $event)
    {
        $comment = $event->getComment();

        if ($comment->getState() == null) {
            $comment->setState(0);
        }

        if ($comment->getCreatedAt() == null) {
            $comment->setCreatedAt(new \DateTime('now'));
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
            Events::QUESTION_COMMENT_PRE_PERSIST => 'setDefaultValues',
            Events::QUESTION_VOTE_PRE_PERSIST => 'updateQuestionScore',
        );
    }
}