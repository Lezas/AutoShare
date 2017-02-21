<?php

/**
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace StackExchangeBundle;

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
    const QUESTION_PRE_PERSIST = 'stack_exchange.question.pre_persist';

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
    const QUESTION_POST_PERSIST = 'stack_exchange.question.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Comment.
     *
     * The listener receives a FOS\CommentBundle\Event\CommentEvent
     * instance.
     *
     * @var string
     */
    const QUESTION_CREATE = 'stack_exchange.question.create';

    //ANSWER

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
    const ANSWER_PRE_PERSIST = 'stack_exchange.answer.pre_persist';

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
    const ANSWER_POST_PERSIST = 'stack_exchange.answer.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Comment.
     *
     * The listener receives a FOS\CommentBundle\Event\CommentEvent
     * instance.
     *
     * @var string
     */
    const ANSWER_CREATE = 'stack_exchange.answer.create';

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
