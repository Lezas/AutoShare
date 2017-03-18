<?php

namespace MultiBlogBundle\Model;

interface PageInterface
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
     * Set date
     *
     * @param \DateTime $createAt
     *
     */
    public function setCreatedAt($createAt);

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getCreatedAt();
}