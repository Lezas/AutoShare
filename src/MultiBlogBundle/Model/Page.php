<?php

namespace MultiBlogBundle\Model;

use StackExchangeBundle\Model\SignedInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class Page implements PageInterface, SignedInterface
{
    /**
     * {@inheritdoc}
     */
    protected $id;

    /**
     * {@inheritdoc}
     */
    protected $title;

    /**
     * {@inheritdoc}
     */
    protected $body;

    /**
     * {@inheritdoc}
     */
    protected $author;

    /**
     * {@inheritdoc}
     */
    protected $createdAt;


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
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
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }

    /**
     * Set date
     *
     * @param \DateTime $createAt
     *
     * @return PageInterface
     */
    public function setCreatedAt($createAt)
    {
        $this->createdAt = $createAt;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}