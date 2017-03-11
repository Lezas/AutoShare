<?php

namespace StackExchangeBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use StackExchangeBundle\Model\CommentInterface;

use StackExchangeBundle\Model\QuestionCommentManager as BaseCommentManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class QuestionCommentManager extends BaseCommentManager
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
        $this->repository = $em->getRepository(QuestionComment::class);

        $metadata = $em->getClassMetadata(QuestionComment::class);

        $this->class = $metadata->name;
    }

    /**
     * Finds one comment thread by the given criteria
     *
     * @param  array $criteria
     * @return Question
     */
    public function findCommentBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findCommentsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string $id
     * @return Question
     *
     */
    public function findCommentById($id)
    {
        return $this->findCommentBy(array('id' => $id));
    }

    /**
     * Finds all threads.
     *
     * @return array of Question
     */
    public function findAllComments()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewComment(CommentInterface $comment)
    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($comment);
    }

    /**
     * Returns the fully qualified comment thread class name
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
     * @param CommentInterface $comment
     */
    protected function doSaveComment(CommentInterface $comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
    }
}

