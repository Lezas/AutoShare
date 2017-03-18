<?php

namespace MultiBlogBundle\Model;

use MultiBlogBundle\Entity\Tag;
use MultiBlogBundle\Event\TagEvent;
use MultiBlogBundle\Event\TagPersistEvent;
use MultiBlogBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class TagManager implements ManagerInterface
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

    public function createTag()
    {
        $class = $this->getClass();

        /** @var Tag $tag */
        $tag = new $class;


        $event = new TagEvent($tag);
        $this->dispatcher->dispatch(Events::TAG_CREATE, $event);

        return $tag;
    }

    /**
     * Persists Question.
     *
     * @param Tag $tag
     * @return bool
     */
    public function saveTag(Tag $tag)
    {
        $event = new TagPersistEvent($tag);
        $this->dispatcher->dispatch(Events::TAG_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveTag($tag);

        $event = new TagEvent($tag);
        $this->dispatcher->dispatch(Events::TAG_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param Tag $tag
     * @return
     */
    abstract protected function doSaveTag(Tag $tag);
}