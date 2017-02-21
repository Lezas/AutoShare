<?php

namespace StackExchangeBundle\Entity;

use Beelab\TagBundle\Tag\TagInterface;
use Beelab\TagBundle\Tag\TaggableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MyAutoBundle\Entity\Thread;
use MyAutoBundle\Entity\User;
use StackExchangeBundle\Model\QuestionInterface;
use StackExchangeBundle\Model\SignedInterface;
use StackExchangeBundle\Model\VotableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Answer
 *
 * @ORM\Table(name="question")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Question implements TaggableInterface, VotableInterface, SignedInterface
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date")
     */
    private $createAt;

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
     *
     *
     * @ORM\ManyToMany(targetEntity="StackExchangeBundle\Entity\Tag", inversedBy="questions", cascade={"persist"})
     * @ORM\JoinTable(name="question_tag")
     * @var Tag[]|ArrayCollection
     */
    protected $tags;

    // note: if you generated code with SensioGeneratorBundle, you need
    // to replace "Tag" with "TagInterface" where appropriate

    /**
     * @ORM\OneToOne(targetEntity = "MyAutoBundle\Entity\Thread")
     * @ORM\JoinColumn(name = "thread_id", referencedColumnName = "id")
     * @var Thread
     */
    private $thread;


    /**
     * @ORM\ManyToOne(targetEntity = "MyAutoBundle\Entity\User", inversedBy = "Question")
     * @ORM\JoinColumn(name = "author_id", referencedColumnName = "id")
     * @var User
     */
    protected $author;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $score;

    /**
     * @ORM\OneToMany(targetEntity="StackExchangeBundle\Entity\Answer", mappedBy="question")
     * @var Answer[]|ArrayCollection
     */
    private $answers;

    //-----Base Class functions-----//

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     *
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set date
     *
     * @param \DateTime $createAt
     *
     * @return Question
     */
    public function setCreatedAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createAt;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Question
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
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Question
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

    public function addAnswer(Answer $answer)
    {
        $this->answers->add($answer);
        $answer->setQuestion($this);
    }

    public function removeAnswer(Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    public function hasAnswer(Answer $answer)
    {
        return $this->answers->contains($answer);
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    //------Author Functions----//

    /**
     * {@inheritdoc}
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }

    /**
     * {@inheritdoc}
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
     *
     * @return Question
     */
    public function setThread(Thread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \MyAutoBundle\Entity\Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    //------SCORE----//

    /**
     * {@inheritdoc}
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * {@inheritdoc}
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementScore($by = 1)
    {
        $this->score = $this->score + $by;

        return $this->score;
    }

    //-----TAGS------//
    /**
     * {@inheritdoc}
     */
    public function addTag($tag)
    {
        $this->tags->add($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function removeTag(TagInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function hasTag(TagInterface $tag)
    {
        return $this->tags->contains($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function getTagNames()
    {
        return empty($this->tagsText) ? [] : array_map('trim', explode(',', $this->tagsText));
    }
}