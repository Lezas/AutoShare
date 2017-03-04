<?php

namespace StackExchangeBundle\Event;

use StackExchangeBundle\Entity\Question;
use Symfony\Component\EventDispatcher\Event;
use StackExchangeBundle\Model\Vote as BaseVote;


/**
 * An event that occurs related to a comment.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class AnswerVoteEvent extends Event
{
    private $vote;

    /**
     * Constructs an event.
     *
     * @param BaseVote $vote
     */
    public function __construct(BaseVote $vote)
    {
        $this->vote = $vote;
    }

    /**
     * @return BaseVote
     */
    public function getVote()
    {
        return $this->vote;
    }
}
