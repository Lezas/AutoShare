<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-11
 * Time: 18:15
 */

namespace StackExchangeBundle\Entity;


use MyAutoBundle\Entity\Thread;
use MyAutoBundle\Entity\User;
use StackExchangeBundle\Model\SignedInterface;
use StackExchangeBundle\Model\VotableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Answer implements VotableInterface, SignedInterface
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $score;

    /**
     * @ORM\OneToOne(targetEntity = "MyAutoBundle\Entity\Thread")
     * @ORM\JoinColumn(name = "thread_id", referencedColumnName = "id")
     * @var Thread
     */
    private $thread;

    /**
     * @ORM\ManyToOne(targetEntity = "MyAutoBundle\Entity\User", inversedBy = "Answer")
     * @ORM\JoinColumn(name = "author_id", referencedColumnName = "id")
     * @var User
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity = "StackExchangeBundle\Entity\Question")
     * @ORM\JoinColumn(name = "question_id", referencedColumnName = "id")
     * @var User
     */
    private $question;

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
     * @param Question $question
     * @return $this
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return User
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
     * @param \MyAutoBundle\Entity\Thread $thread
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


}