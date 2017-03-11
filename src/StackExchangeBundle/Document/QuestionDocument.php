<?php

namespace StackExchangeBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Collection\Collection;
use StackExchangeBundle\Document\TagDocument;

/**
 * @ES\Document(type="question")
 */
class QuestionDocument
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
     * @ES\Property(name="question_id", type="text")
     */
    public $question_id;

    /**
     * @var string
     *
     * @ES\Property(name="title", type="text")
     */
    public $title;

    /**
     * @var string
     *
     * @ES\Property(name="body", type="text")
     */
    public $body;

    /**
     * @var ContentMetaObject
     *
     * @ES\Embedded(class="StackExchangeBundle:TagDocument", multiple=true)
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new Collection();
    }

    /**
     * Adds variant to the collection.
     *
     * @param TagDocument $tag
     * @return $this
     */
    public function addTag(TagDocument $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setQuestionId($id)
    {
        $this->question_id = $id;
    }

    public function getQuestionId()
    {
        return $this->question_id;
    }

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

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}