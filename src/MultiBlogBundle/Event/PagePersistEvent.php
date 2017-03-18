<?php

namespace MultiBlogBundle\Event;

/**
 * An event related to a persisting event that can be
 * cancelled by a listener.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class PagePersistEvent extends PageEvent
{
    /**
     * @var bool
     */
    private $abortPersistence = false;

    /**
     * Indicates that the persisting operation should not proceed.
     */
    public function abortPersistence()
    {
        $this->abortPersistence = true;
    }

    /**
     * Checks if a listener has set the event to abort the persisting
     * operation.
     *
     * @return bool
     */
    public function isPersistenceAborted()
    {
        return $this->abortPersistence;
    }
}
