<?php

namespace StackExchangeBundle\EventListener;

use Psr\Log\LoggerInterface;
use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Event\AnswerEvent;
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
class AnswerBlamerListener implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface|SecurityContextInterface
     */
    private $authorizationChecker;

    /**
     * @var TokenStorageInterface|SecurityContextInterface
     */
    private $tokenStorage;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param AuthorizationCheckerInterface|SecurityContextInterface $authorizationChecker
     * @param SecurityContextInterface|SecurityContextInterface      $tokenStorage
     * @param LoggerInterface                                        $logger
     */
    public function __construct($authorizationChecker, $tokenStorage, LoggerInterface $logger = null)
    {
        if (!$authorizationChecker instanceof AuthorizationCheckerInterface && !$authorizationChecker instanceof SecurityContextInterface) {
            throw new \InvalidArgumentException('Argument 1 should be an instance of Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface or Symfony\Component\Security\Core\SecurityContextInterface');
        }

        if (!$tokenStorage instanceof TokenStorageInterface && !$tokenStorage instanceof SecurityContextInterface) {
            throw new \InvalidArgumentException('Argument 2 should be an instance of Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface or Symfony\Component\Security\Core\SecurityContextInterface');
        }

        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * Assigns the currently logged in user to a Comment.
     *
     * @param AnswerEvent $event
     */
    public function blame(AnswerEvent $event)
    {
        /** @var Answer $answer */
        $answer = $event->getAnswer();

        if (!$answer instanceof SignedInterface) {
            if ($this->logger) {
                $this->logger->debug("Comment does not implement SignedInterface, skipping");
            }

            return;
        }

        if (null === $this->tokenStorage->getToken()) {
            if ($this->logger) {
                $this->logger->debug("There is no firewall configured. We cant get a user.");
            }

            return;
        }

        if (null === $answer->getAuthor() && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $answer->setAuthor($this->tokenStorage->getToken()->getUser());
        }
        if (null === $answer->isAccepted()) {
            $answer->setAccepted(false);
        }

    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(Events::ANSWER_PRE_PERSIST => 'blame');
    }
}
