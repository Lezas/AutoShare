<?php

namespace StackExchangeBundle\Model;

use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Entity\AnswerComment;
use StackExchangeBundle\Event\AnswerCommentEvent;
use StackExchangeBundle\Event\AnswerCommentPersistEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AnswerCommentManager implements QuestionCommentManagerInterface
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
     * @param Answer $answer
     * @param null $user
     * @return AnswerComment
     */
    public function createComment(Answer $answer, $user = null)
    {
        $class = $this->getClass();

        /** @var AnswerComment $comment */
        $comment = new $class;
        $comment->setAnswer($answer);
        $comment->setAuthor($user);

        $event = new AnswerCommentEvent($comment);
        $this->dispatcher->dispatch(Events::ANSWER_COMMENT_CREATE, $event);

        return $comment;
    }

    /**
     * Persists Question.
     *
     * @param CommentInterface $comment
     * @return bool
     */
    public function saveComment(CommentInterface $comment)
    {
        $event = new AnswerCommentPersistEvent($comment);
        $this->dispatcher->dispatch(Events::ANSWER_COMMENT_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveComment($comment);

        $event = new AnswerCommentEvent($comment);
        $this->dispatcher->dispatch(Events::ANSWER_COMMENT_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param CommentInterface $comment
     * @return
     */
    abstract protected function doSaveComment(CommentInterface $comment);
}