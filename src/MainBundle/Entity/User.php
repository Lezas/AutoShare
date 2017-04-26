<?php

namespace MainBundle\Entity;

use CarShowBundle\Entity\Car;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use MultiBlogBundle\Entity\Page;
use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Entity\AnswerComment;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Entity\QuestionComment;
use FOS\MessageBundle\Model\ParticipantInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity()
 */
class User extends BaseUser implements ParticipantInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="CarShowBundle\Entity\Car", mappedBy="user")
     * @var Car[]|ArrayCollection
     */
    protected $car;

    /**
     * @ORM\ManyToMany(targetEntity="CarShowBundle\Entity\Car", mappedBy="favoritedUsers")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     * @ORM\JoinTable(name="user_favorites")
     */
    protected $favorites;

    /**
     * @ORM\ManyToMany(targetEntity="CarShowBundle\Entity\Car", mappedBy="likedUsers")
     * @ORM\JoinTable(name="user_likes")
     */
    protected $liked;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="main_photo", referencedColumnName="id")
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="StackExchangeBundle\Entity\Question", mappedBy="author")
     * @var Question[]|ArrayCollection
     */
    protected $questions;

    /**
     * @ORM\OneToMany(targetEntity="MultiBlogBundle\Entity\Page", mappedBy="author")
     * @var Page[]|ArrayCollection
     */
    protected $pages;

    /**
     * @ORM\OneToMany(targetEntity="StackExchangeBundle\Entity\QuestionComment", mappedBy="user")
     * @var QuestionComment[]|ArrayCollection
     */
    protected $questionsComments;

    /**
     * @ORM\OneToMany(targetEntity="StackExchangeBundle\Entity\Answer", mappedBy="author")
     * @var Answer[]|ArrayCollection
     */
    protected $answers;

    /**
     * @ORM\OneToMany(targetEntity="StackExchangeBundle\Entity\AnswerComment", mappedBy="user")
     * @var AnswerComment[]|ArrayCollection
     */
    protected $answersComments;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    public function __construct()
    {
        $this->Auto = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->liked = new ArrayCollection();
        $this->questionsComments = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->answersComments = new ArrayCollection();
        $this->answers = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Add auto
     *
     * @param \CarShowBundle\Entity\Car $car
     *
     * @return User
     */
    public function addAuto(Car $car)
    {
        $this->car[] = $car;

        return $this;
    }

    /**
     * Remove auto
     *
     * @param \CarShowBundle\Entity\Car $car
     */
    public function removeAuto(Car $car)
    {
        $this->car->removeElement($car);
    }

    /**
     * Get all cars
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCars()
    {
        return $this->car;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    public function addAutoToFavorites(Car $auto)
    {
        $this->favorites[] = $auto;
    }

    public function removeAutoFromFavorites(Car $auto)
    {
        $this->favorites->removeElement($auto);
    }

    public function isAutoFavorited(Car $auto)
    {
        return $this->favorites->contains($auto);
    }


    public function addAutoToLiked(Car $auto)
    {
        $this->liked[] = $auto;
    }

    public function getLikedAutos()
    {
        return $this->liked;
    }

    public function removeAutoFromLiked(Car $auto)
    {
        $this->liked->removeElement($auto);
    }

    public function isAutoLiked(Car $auto)
    {
        return $this->liked->contains($auto);
    }

    public function addQuestionComment($comment)
    {
        $this->questionsComments[] = $comment;

        return $this;
    }

    public function removeQuestionComment( $comment)
    {
        $this->questionsComments->removeElement($comment);
    }

    /**
     * Get auto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestionComments()
    {
        return $this->questionsComments;
    }


    public function addAnswerComment($comment)
    {
        $this->answersComments[] = $comment;

        return $this;
    }

    public function removeAnswerComment( $comment)
    {
        $this->answersComments->removeElement($comment);
    }

    /**
     * Get auto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswerComments()
    {
        return $this->answersComments;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @return ArrayCollection|Page[]
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param ArrayCollection|Page[] $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
