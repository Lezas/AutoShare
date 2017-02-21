<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 11:58
 */

namespace StackExchangeBundle\Model;

use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Event\QuestionPersistEvent;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class QuestionManager implements QuestionManagerInterface
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

    public function createQuestion()
    {
        $class = $this->getClass();

        /** @var Question $question */
        $question = new $class;


        $event = new QuestionEvent($question);
        $this->dispatcher->dispatch(Events::QUESTION_CREATE, $event);

        return $question;
    }

    /**
     * Persists Question.
     *
     * @param Question $question
     * @return bool
     */
    public function saveQuestion(Question $question)
    {

        $event = new QuestionPersistEvent($question);
        $this->dispatcher->dispatch(Events::QUESTION_PRE_PERSIST, $event);



        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveQuestion($question);

        $event = new QuestionEvent($question);
        $this->dispatcher->dispatch(Events::QUESTION_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param Question $question
     */
    abstract protected function doSaveQuestion(Question $question);
}