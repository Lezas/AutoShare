<?php

namespace MultiBlogBundle\Entity;

use Beelab\TagBundle\Tag\TaggableInterface;
use Beelab\TagBundle\Tag\TagInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\User;
use MultiBlogBundle\Model\Page as BasePage;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Post
 *
 * @ORM\Table(name="mb__page")
 * @ORM\Entity()
 */
class Page extends BasePage implements TaggableInterface
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
     * @ORM\ManyToOne(targetEntity = "MainBundle\Entity\User", inversedBy = "pages")
     * @ORM\JoinColumn(name = "author_id", referencedColumnName = "id")
     * @var User
     */
    protected $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     *
     * @ORM\ManyToMany(targetEntity="MultiBlogBundle\Entity\Tag", mappedBy="pages", cascade={"persist"})
     * @var Tag[]|ArrayCollection
     */
    protected $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }


    //-----TAGS------//
    /**
     * {@inheritdoc}
     */
    public function addTag(TagInterface $tag)
    {
        /** @var Tag $tag */
        $this->tags->add($tag);
        $tag->addPage($this);
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

