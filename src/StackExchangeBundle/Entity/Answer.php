<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-11
 * Time: 18:15
 */

namespace StackExchangeBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use CarShowBundle\Entity\Thread;
use MainBundle\Entity\User;
use StackExchangeBundle\Model\CommentableInterface;
use StackExchangeBundle\Model\CommentInterface;
use StackExchangeBundle\Model\SignedInterface;
use StackExchangeBundle\Model\VotableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="se__answer")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Answer implements VotableInterface, SignedInterface, CommentableInterface
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     *
     */
    private $deleted;

    /**
     * @ORM\Column(type="boolean", options={"default" = false})
     *
     */
    private $accepted;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $score;

    /**
     * @ORM\OneToOne(targetEntity = "CarShowBundle\Entity\Thread")
     * @ORM\JoinColumn(name = "thread_id", referencedColumnName = "id")
     * @var Thread
     */
    private $thread;

    /**
     * @ORM\ManyToOne(targetEntity = "MainBundle\Entity\User", inversedBy = "answers")
     * @ORM\JoinColumn(name = "author_id", referencedColumnName = "id")
     * @var User
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity = "StackExchangeBundle\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(name = "question_id", referencedColumnName = "id")
     * @var Question
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity = "StackExchangeBundle\Entity\AnswerComment", mappedBy="answer")
     * @var AnswerComment[]|ArrayCollection
     */
    protected $comments;



    //------Base class functions----//
    /**
     *
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }

    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

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
     * Set date
     *
     * @param $createdAt
     * @return Answer
     *
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * Set text
     *
     * @param string $text
     *
     * @return Answer
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
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

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Answer
     */
    public function setDeleted( $deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Is private
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deleted
     *
     * @param bool $accepted
     * @return Answer
     *
     */
    public function setAccepted( $accepted)
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * Is private
     *
     * @return boolean
     */
    public function isAccepted()
    {
        return $this->accepted;
    }

    /**
     * @param Question $question
     * @return $this
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }


    //------Author Functions----//

    /**
     * @param UserInterface $author
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }

    /**
     * @return UserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    //------COMMENTING-----//

    /**
     * Set thread
     *
     * @param \CarShowBundle\Entity\Thread $thread
     *
     * @return Answer
     */
    public function setThread(Thread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    //------SCORE----//

    public function setScore($score)
    {
        $this->score = $score;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function incrementScore($by = 1)
    {
        $this->score = $this->score + $by;
    }

    //------COMMENT----//

    /**
     * {@inheritdoc}
     */
    public function addComment(CommentInterface $comment)
    {
        $this->comments->add($comment);
    }


    /**
     * {@inheritdoc}
     */
    public function removeComment(CommentInterface $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * {@inheritdoc}
     */
    public function hasComment(CommentInterface $comment)
    {
        return $this->comments->contains($comment);
    }

    /**
     * {@inheritdoc}
     */
    public function getComments()
    {
        return $this->comments;
    }


}