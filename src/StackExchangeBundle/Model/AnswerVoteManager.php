<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 11:58
 */

namespace StackExchangeBundle\Model;

use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Entity\AnswerVote;
use StackExchangeBundle\Entity\QuestionVote;
use StackExchangeBundle\Event\AnswerVoteEvent;
use StackExchangeBundle\Event\AnswerVotePersistEvent;
use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Event\QuestionPersistEvent;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Event\QuestionVoteEvent;
use StackExchangeBundle\Event\QuestionVotePersistEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use StackExchangeBundle\Model\Vote as BaseVote;

abstract class AnswerVoteManager implements AnswerVoteManagerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher A dispatcher instance.
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Question $question
     * @param null $user
     * @return AnswerVote
     */
    public function createVote(Answer $answer, $user = null)
    {
        $class = $this->getClass();

        /** @var AnswerVote $vote */
        $vote = new $class;
        $vote->setAnswer($answer);
        $vote->setUser($user);

        $event = new AnswerVoteEvent($vote);
        $this->dispatcher->dispatch(Events::ANSWER_VOTE_CREATE, $event);

        return $vote;
    }

    /**
     * Persists Question.
     *
     * @param Vote $vote
     * @return bool
     */
    public function saveVote(BaseVote $vote)
    {

        $event = new AnswerVotePersistEvent($vote);
        $this->dispatcher->dispatch(Events::ANSWER_VOTE_PRE_PERSIST, $event);



        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveVote($vote);

        $event = new AnswerVoteEvent($vote);
        $this->dispatcher->dispatch(Events::ANSWER_VOTE_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param BaseVote $vote
     * @return
     */
    abstract protected function doSaveVote(BaseVote $vote);
}