<?php

namespace MultiBlogBundle\Event;

use MultiBlogBundle\Model\PageInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a comment.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class PageEvent extends Event
{
    private $page;

    /**
     * Constructs an event.
     *
     * @param PageInterface $post
     */
    public function __construct(PageInterface $page)
    {
        $this->page = $page;
    }

    /**
     * @return PostInterface
     */
    public function getPage()
    {
        return $this->page;
    }
}
