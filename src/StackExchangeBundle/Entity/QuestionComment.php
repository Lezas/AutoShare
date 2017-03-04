<?php

namespace StackExchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MyAutoBundle\Entity\User;
use StackExchangeBundle\Model\CommentInterface;

/**
 * QuestionComment
 *
 * @ORM\Table(name="question_comment")
 * @ORM\Entity(repositoryClass="StackExchangeBundle\Repository\QuestionCommentRepository")
 */
class QuestionComment implements CommentInterface
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
     * @ORM\ManyToOne(targetEntity = "StackExchangeBundle\Entity\Question")
     * @var Question
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity = "MyAutoBundle\Entity\User", inversedBy = "QuestionComment")
     * @ORM\JoinColumn(name = "author_id", referencedColumnName = "id")
     * @var User
     */
    private $author;

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
     * @return int
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
}

