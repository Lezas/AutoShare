<?php

namespace CarShowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="CarShowBundle\Repository\PostRepository")
 */
class Post
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
     * @ORM\Column(name="mileage", type="string", length=255)
     */
    private $mileage;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

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
     * @ORM\ManyToOne(targetEntity = "CarShowBundle\Entity\Car", inversedBy = "posts")
     * @ORM\JoinColumn(name = "car_id", referencedColumnName = "id")
     * @var Car
     */
    private $car;

    /**
     * @ORM\OneToOne(targetEntity = "CarShowBundle\Entity\Thread")
     * @ORM\JoinColumn(name = "thread_id", referencedColumnName = "id")
     * @var Thread
     */
    private $thread;

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
     * Set mileage
     *
     * @param string $mileage
     *
     * @return Post
     */
    public function setMileage($mileage)
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * Get mileage
     *
     * @return string
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
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
     * @param \DateTime $date
     *
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Post
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
     * Set car
     *
     * @param \CarShowBundle\Entity\Car car
     *
     * @return Post
     */
    public function setAuto(Car $car = null)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car
     *
     * @return \CarShowBundle\Entity\Car
     */
    public function getAuto()
    {
        return $this->car;
    }

    /**
     * Set thread
     *
     * @param \CarShowBundle\Entity\Thread $thread
     *
     * @return Post
     */
    public function setThread(Thread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \CarShowBundle\Entity\Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Post
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
}

