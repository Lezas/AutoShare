<?php

namespace MultiBlogBundle\Model;

interface PostInterface
{
      /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Post
     */
    public function setBody($body);

    /**
     * Get body
     *
     * @return string
     */
    public function getBody();

    /**
     * Set user
     *
     * @param $author
     *
     * @return Post
     */
    public function setAuthor($author);
    /**
     * Get user
     *
     * @return string
     */
    public function getAuthor();
}