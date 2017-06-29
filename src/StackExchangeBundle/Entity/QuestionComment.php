<?php

namespace StackExchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\User;
use StackExchangeBundle\Model\CommentInterface;
use StackExchangeBundle\Model\Comment as BaseComment;

/**
 * QuestionComment
 *
 * @ORM\Table(name="se__question_comment")
 * @ORM\Entity()
 */
class QuestionComment extends BaseComment implements CommentInterface
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
     * @ORM\ManyToOne(targetEntity = "StackExchangeBundle\Entity\Question", inversedBy="comments")
     * @var Question
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity = "MainBundle\Entity\User", inversedBy="questionsComments")
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
     * @param $question
     * @return $this
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor($author)
    {
        $this->user = $author;
        /** @var User $author */
        $author->addQuestionComment($this);

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

