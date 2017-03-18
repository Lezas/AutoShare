<?php

namespace MultiBlogBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use MultiBlogBundle\Model\PageInterface;
use MultiBlogBundle\Model\PageManager as BasePageManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PageManager extends BasePageManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $em)
    {
        parent::__construct($dispatcher);

        $this->em = $em;
        $this->repository = $em->getRepository(Page::class);

        $metadata = $em->getClassMetadata(Page::class);

        $this->class = $metadata->name;
    }

    /**
     * {@inheritDoc}
     */
    public function findPagesBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string $id
     * @return PageInterface
     *
     */
    public function findPageById($id)
    {
        return $this->findPageBy(array('id' => $id));
    }

    /**
     * Finds one comment thread by the given criteria
     *
     * @param  array $criteria
     * @return PageInterface
     */
    public function findPageBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Finds all threads.
     *
     * @return array of Question
     */
    public function findAllPages()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewPost(PageInterface $page)

    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($page);
    }

    /**
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }


    /**
     * Saves Question
     *
     * @param PageInterface $post
     */
    protected function doSavePost(PageInterface $page)
    {
        $this->em->persist($page);
        $this->em->flush();
    }


}