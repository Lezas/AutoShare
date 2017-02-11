<?php

namespace StackExchangeBundle\Model;

/**
 * A comment that may be voted on.
 *
 */
interface VotableInterface
{
    /**
     * Sets the score of the comment.
     *
     * @param integer $score
     */
    public function setScore($score);

    /**
     * Returns the current score of the comment.
     *
     * @return integer
     */
    public function getScore();

    /**
     * Increments the comment score by the provided
     * value.
     *
     * @param integer $by
     * @return integer The new comment score
     */
    public function incrementScore($by = 1);
}