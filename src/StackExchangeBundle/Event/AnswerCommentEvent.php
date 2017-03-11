<?php

namespace StackExchangeBundle\Event;

use StackExchangeBundle\Model\CommentInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a comment.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class AnswerCommentEvent extends Event
{
    private $comment;

    /**
     * Constructs an event.
     *
     * @param CommentInterface $comment
     */
    public function __construct(CommentInterface $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return CommentInterface
     */
    public function getComment()
    {
        return $this->comment;
    }
}
