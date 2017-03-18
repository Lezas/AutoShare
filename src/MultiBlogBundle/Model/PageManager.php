<?php

namespace MultiBlogBundle\Model;


use MultiBlogBundle\Event\PageEvent;
use MultiBlogBundle\Event\PagePersistEvent;
use MultiBlogBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class PageManager implements ManagerInterface
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

    /**
     * @return PageInterface
     * @internal param Question $question
     */
    public function createPage()
    {
        $class = $this->getClass();

        $page = new $class;

        $event = new PageEvent($page);
        $this->dispatcher->dispatch(Events::PAGE_CREATE, $event);

        return $page;
    }

    /**
     * Persists Question.
     *
     * @param PageInterface $page
     * @return bool
     */
    public function savePage(PageInterface $page)
    {

        $event = new PagePersistEvent($page);
        $this->dispatcher->dispatch(Events::PAGE_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSavePost($page);

        $event = new PageEvent($page);
        $this->dispatcher->dispatch(Events::PAGE_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param PageInterface $post
     * @return
     */
    abstract protected function doSavePost(PageInterface $post);
}