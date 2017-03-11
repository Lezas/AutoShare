<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 19:04
 */

namespace StackExchangeBundle\EventListener;

use StackExchangeBundle\Event\AnswerCommentEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AnswerCommentListener implements EventSubscriberInterface
{
    public function setDefaultValues(AnswerCommentEvent $event)
    {
        $comment = $event->getComment();

        if ($comment->getState() == null) {
            $comment->setState(0);
        }

        if ($comment->getCreatedAt() == null) {
            $comment->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::ANSWER_COMMENT_PRE_PERSIST => 'setDefaultValues',
        );
    }
}