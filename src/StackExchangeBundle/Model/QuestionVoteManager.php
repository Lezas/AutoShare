<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 11:58
 */

namespace StackExchangeBundle\Model;

use StackExchangeBundle\Entity\QuestionVote;
use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Event\QuestionPersistEvent;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Event\QuestionVoteEvent;
use StackExchangeBundle\Event\QuestionVotePersistEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use StackExchangeBundle\Model\Vote as BaseVote;

abstract class QuestionVoteManager implements QuestionVoteManagerInterface
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
     * @return QuestionVote
     */
    public function createVote(Question $question, $user = null)
    {
        $class = $this->getClass();

        /** @var QuestionVote $vote */
        $vote = new $class;
        $vote->setQuestion($question);
        $vote->setUser($user);

        $event = new QuestionVoteEvent($vote);
        $this->dispatcher->dispatch(Events::QUESTION_VOTE_CREATE, $event);

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

        $event = new QuestionVotePersistEvent($vote);
        $this->dispatcher->dispatch(Events::QUESTION_VOTE_PRE_PERSIST, $event);



        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveVote($vote);

        $event = new QuestionVoteEvent($vote);
        $this->dispatcher->dispatch(Events::QUESTION_VOTE_POST_PERSIST, $event);

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