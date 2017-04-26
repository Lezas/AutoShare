<?php

namespace CarShowBundle\Model;

use CarShowBundle\Entity\Post;
use CarShowBundle\Event\PostEvent;
use CarShowBundle\Event\PostPersistEvent;
use CarShowBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class PostManager implements ManagerInterface
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
     * @return Post
     */
    public function createPost()
    {
        $class = $this->getClass();

        $post = new $class;

        $event = new PostEvent($post);
        $this->dispatcher->dispatch(Events::POST_CREATE, $event);

        return $post;
    }

    /**
     * Persists Question.
     *
     * @param Post $post
     * @return bool
     */
    public function savePost(Post $post)
    {

        $event = new PostPersistEvent($post);
        $this->dispatcher->dispatch(Events::POST_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSavePost($post);

        $event = new PostEvent($post);
        $this->dispatcher->dispatch(Events::POST_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param Post $post
     * @return
     * @internal param Answer $answer
     * @internal param Question $question
     */
    abstract protected function doSavePost(Post $post);
}