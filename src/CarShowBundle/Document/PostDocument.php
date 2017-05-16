<?php

namespace CarShowBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * @ES\Object
 */
class PostDocument
{
    /**
     * @var string
     *
     * @ES\Id()
     */
    public $id;

    /**
     * @var string
     *
     * @ES\Property(name="title", type="text")
     */
    public $title;

    /**
     * @var string
     *
     * @ES\Property(name="text", type="text")
     */
    public $text;

    /**
     * @var string
     *
     * @ES\Property(name="mileage", type="text")
     */
    public $mileage;

    /**
     * @var string
     *
     * @ES\Property(name="createdAt", type="text")
     */
    public $createdAt;

    /**
     * Sets title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * @param string $mileage
     */
    public function setMileage($mileage)
    {
        $this->mileage = $mileage;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}