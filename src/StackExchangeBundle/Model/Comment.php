<?php
/**
 * Created by Lezas.
 * Date: 2017-03-04
 * Time: 21:51
 */

namespace StackExchangeBundle\Model;


use Symfony\Component\Security\Core\User\UserInterface;

abstract class Comment
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var UserInterface
     */
    private $author;

    /**
     * @var string
     *
     */
    private $body;

    /**
     * @var \DateTime
     *
     */
    private $createdAt;

    /**
     * @var int
     *
     */
    private $state;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}