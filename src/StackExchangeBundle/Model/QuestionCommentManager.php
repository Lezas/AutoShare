<?php

namespace StackExchangeBundle\Model;

use StackExchangeBundle\Entity\QuestionComment;
use StackExchangeBundle\Event\QuestionCommentEvent;
use StackExchangeBundle\Event\QuestionCommentPersistEvent;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class QuestionCommentManager implements QuestionCommentManagerInterface
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
     * @return QuestionComment
     */
    public function createComment(Question $question, $user = null)
    {
        $class = $this->getClass();

        /** @var QuestionComment $comment */
        $comment = new $class;
        $comment->setQuestion($question);
        $comment->setAuthor($user);

        $event = new QuestionCommentEvent($comment);
        $this->dispatcher->dispatch(Events::QUESTION_COMMENT_CREATE, $event);

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
        $event = new QuestionCommentPersistEvent($comment);
        $this->dispatcher->dispatch(Events::QUESTION_COMMENT_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveComment($comment);

        $event = new QuestionCommentEvent($comment);
        $this->dispatcher->dispatch(Events::QUESTION_COMMENT_POST_PERSIST, $event);

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