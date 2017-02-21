<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 11:58
 */

namespace StackExchangeBundle\Model;

use StackExchangeBundle\Entity\Tag;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Event\TagEvent;
use StackExchangeBundle\Event\TagPersistEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class TagManager implements TagManagerInterface
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