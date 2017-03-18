<?php

/**
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MultiBlogBundle;

final class Events
{
    /**
     * The PRE_PERSIST event occurs prior to the persistence backend
     * persisting the Comment.
     *
     * This event allows you to modify the data in the Comment prior
     * to persisting occuring. The listener receives a
     * FOS\CommentBundle\Event\CommentPersistEvent instance.
     *
     * Persisting of the comment can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const PAGE_PRE_PERSIST = 'multi_blog.page.pre_persist';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Comment.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Comment to be persisted before performing
     * those actions. The listener receives a
     * FOS\CommentBundle\Event\CommentEvent instance.
     *
     * @var string
     */
    const PAGE_POST_PERSIST = 'multi_blog.page.post_persist';

    /**
     * The PRE_PERSIST event occurs prior to the persistence backend
     * persisting the Comment.
     *
     * This event allows you to modify the data in the Comment prior
     * to persisting occuring. The listener receives a
     * FOS\CommentBundle\Event\CommentPersistEvent instance.
     *
     * Persisting of the comment can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const PAGE_PRE_UPDATE = 'multi_blog.page.pre_update';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Comment.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Comment to be persisted before performing
     * those actions. The listener receives a
     * FOS\CommentBundle\Event\CommentEvent instance.
     *
     * @var string
     */
    const PAGE_POST_UPDATE = 'multi_blog.page.post_update';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Comment.
     *
     * The listener receives a FOS\CommentBundle\Event\CommentEvent
     * instance.
     *
     * @var string
     */
    const PAGE_CREATE = 'multi_blog.page.create';

    //TAG

    /**
     * The PRE_PERSIST event occurs prior to the persistence backend
     * persisting the Comment.
     *
     * This event allows you to modify the data in the Comment prior
     * to persisting occuring. The listener receives a
     * FOS\CommentBundle\Event\CommentPersistEvent instance.
     *
     * Persisting of the comment can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const TAG_PRE_PERSIST = 'stack_exchange.tag.pre_persist';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Comment.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Comment to be persisted before performing
     * those actions. The listener receives a
     * FOS\CommentBundle\Event\CommentEvent instance.
     *
     * @var string
     */
    const TAG_POST_PERSIST = 'stack_exchange.tag.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Comment.
     *
     * The listener receives a FOS\CommentBundle\Event\CommentEvent
     * instance.
     *
     * @var string
     */
    const TAG_CREATE = 'stack_exchange.tag.create';

}
