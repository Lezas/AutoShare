<?php

namespace MultiBlogBundle\EventListener;

use Psr\Log\LoggerInterface;
use MultiBlogBundle\Event\TagEvent;
use MultiBlogBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Blames a comment using Symfony2 security component
 *
 */
class TagScoreListener implements EventSubscriberInterface
{

    /**
     * Assigns the currently logged in user to a Comment.
     *
     * @param TagEvent $event
     */
    public function count(TagEvent $event)
    {
        $tag = $event->getTag();
        if ($tag->getUsageCount() == null) {
            $tag->setUsageCount(0);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(Events::TAG_PRE_PERSIST => 'count');
    }
}
