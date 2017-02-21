<?php

namespace StackExchangeBundle\EventListener;

use Psr\Log\LoggerInterface;
use StackExchangeBundle\Event\AnswerEvent;
use StackExchangeBundle\Event\TagEvent;
use StackExchangeBundle\Events;
use StackExchangeBundle\Model\SignedInterface;
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
     * @param AnswerEvent|TagEvent $event
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
