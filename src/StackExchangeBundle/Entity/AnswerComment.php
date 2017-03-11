<?php

namespace StackExchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\User;
use StackExchangeBundle\Model\CommentInterface;
use StackExchangeBundle\Model\Comment as BaseComment;

/**
 * QuestionComment
 *
 * @ORM\Table(name="answer_comment")
 * @ORM\Entity()
 */
class AnswerComment extends BaseComment implements CommentInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity = "StackExchangeBundle\Entity\Answer", inversedBy="comments")
     * @var Answer
     */
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity = "MainBundle\Entity\User", inversedBy="answersComments")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="state", type="integer")
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
     * @param $answer
     * @return $this
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor($author)
    {
        $this->user = $author;
        /** @var User $author */
        $author->addAnswerComment($this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser($author)
    {
        $this->user = $author;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
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
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}

