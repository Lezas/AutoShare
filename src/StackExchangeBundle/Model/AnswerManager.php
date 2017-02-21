<?php

namespace StackExchangeBundle\Model;

use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Event\AnswerEvent;
use StackExchangeBundle\Event\AnswerPersistEvent;
use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Event\QuestionPersistEvent;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AnswerManager implements AnswerManagerInterface
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
     * @return Answer
     */
    public function createAnswer(Question $question)
    {
        $class = $this->getClass();

        /** @var Answer $answer */
        $answer = new $class;

        $answer->setQuestion($question);

        $event = new AnswerEvent($answer);
        $this->dispatcher->dispatch(Events::ANSWER_CREATE, $event);

        return $answer;
    }

    /**
     * Persists Question.
     *
     * @param Answer $answer
     * @return bool
     */
    public function saveAnswer(Answer $answer)
    {

        $event = new AnswerPersistEvent($answer);
        $this->dispatcher->dispatch(Events::ANSWER_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveAnswer($answer);

        $event = new AnswerEvent($answer);
        $this->dispatcher->dispatch(Events::ANSWER_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param Answer $answer
     * @return
     * @internal param Question $question
     */
    abstract protected function doSaveAnswer(Answer $answer);
}