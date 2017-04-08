<?php

namespace MultiBlogBundle\Entity;

use Beelab\TagBundle\Tag\TagInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MultiBlogBundle\Model\PageInterface;

/**
 * @ORM\Table(name="mb__tag")
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
     * @ORM\ManyToMany(targetEntity="MultiBlogBundle\Entity\Page", inversedBy="tags")
     * @ORM\JoinTable(name="mb__page_tag")
     * @var Page[]|ArrayCollection
     */
    protected $pages;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $usageCount;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
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


    public function addPage(PageInterface $page)
    {
        $this->pages->add($page);
    }

    public function removePage(PageInterface $page)
    {
        /** @var Page $page */
        $this->pages->removeElement($page);
        $page->removeTag($this);
    }

    public function hasPage(PageInterface $page)
    {
        return $this->pages->contains($page);
    }

    public function getPages()
    {
        return $this->pages;
    }
}