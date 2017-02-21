<?php

namespace StackExchangeBundle\Event;

use StackExchangeBundle\Entity\Answer;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a comment.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class AnswerEvent extends Event
{
    private $answer;

    /**
     * Constructs an event.
     *
     * @param Answer $answer
     */
    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
