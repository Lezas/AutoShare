<?php

namespace MultiBlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MultiBlogBundle\Model\Post as BasePOst;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity()
 */
class Post extends BasePOst
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    protected $author;


}

