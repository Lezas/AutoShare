<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-11
 * Time: 19:13
 */

namespace StackExchangeBundle\Entity;

use Beelab\TagBundle\Tag\TagInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Tag implements TagInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="StackExchangeBundle\Entity\Question", mappedBy="tags")
     * @var Question[]|ArrayCollection
     */
    protected $questions;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $usageCount;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $count
     */
    public function setUsageCount($count)
    {
        $this->usageCount = $count;
    }

    /**
     * @return int
     */
    public function getUsageCount()
    {
        return $this->usageCount;
    }

    /**
     * @param int $by
     * @return int
     */
    public function incrementUsageCount($by = 1)
    {
        $this->usageCount = $this->usageCount + $by;

        return $this->usageCount;
    }


    public function addQuestion(Question $question)
    {
        $this->questions->add($question);
    }

    public function removeQuestion(Question $question)
    {
        $this->questions->removeElement($question);
        $question->removeTag($this);
    }

    public function hasQuestion(Question $question)
    {
        return $this->questions->contains($question);
    }

    public function getQuestions()
    {
        return $this->questions;
    }
}