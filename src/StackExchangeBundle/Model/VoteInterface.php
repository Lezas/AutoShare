<?php

/**
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace StackExchangeBundle\Model;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\ExecutionContextInterface as LegacyExecutionContextInterface;

/**
 * Methods a vote should implement.
 *
 * Copied from FOSCommentBundle
 */
interface VoteInterface
{
    const VOTE_UP = 1;
    const VOTE_DOWN = -1;

    /**
     * @return mixed unique ID for this vote
     */
    public function getId();

    /**
     * @return SignedInterface
     */
    public function getObject();

    /**
     * @param VotableInterface $comment
     */
    public function setObject(VotableInterface $comment);

    /**
     * @return integer the modification applied to the comment by this vote
     */
    public function getValue();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param LegacyExecutionContextInterface|ExecutionContextInterface $context
     */
    public function isVoteValid($context);
}
