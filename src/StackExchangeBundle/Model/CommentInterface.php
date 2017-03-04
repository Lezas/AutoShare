<?php
/**
 * Created by Lezas.
 * Date: 2017-03-04
 * Time: 19:38
 */

namespace StackExchangeBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface CommentInterface
{
    /**
     * @param UserInterface $author
     *
     * @return CommentInterface
     */
    public function setAuthor($author);

    /**
     * Get authorId
     *
     * @return UserInterface
     */
    public function getAuthor();

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CommentInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return CommentInterface
     */
    public function setState($state);

    /**
     * Get state
     *
     * @return int
     */
    public function getState();
}