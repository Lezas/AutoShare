<?php

namespace StackExchangeBundle\Event;

use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Entity\Tag;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a comment.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class TagEvent extends Event
{
    private $tag;

    /**
     * Constructs an event.
     *
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}
