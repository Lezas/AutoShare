<?php

/**
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace StackExchangeBundle\Event;

use StackExchangeBundle\Entity\Question;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a comment.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class QuestionEvent extends Event
{
    private $question;

    /**
     * Constructs an event.
     *
     * @param Question $question
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
