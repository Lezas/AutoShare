<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-14
 * Time: 19:03
 */

namespace StackExchangeBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use StackExchangeBundle\Model\AnswerManager as BaseAnswerManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AnswerManager extends BaseAnswerManager
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
        $this->repository = $em->getRepository(Answer::class);

        $metadata = $em->getClassMetadata(Answer::class);

        $this->class = $metadata->name;
    }

    /**
     * Finds one comment thread by the given criteria
     *
     * @param  array           $criteria
     * @return Answer
     */
    public function findAnswerBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findAnswersBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string          $id
     * @return Answer
     *
     */
    public function findAnswerById($id)
    {
        return $this->findAnswerBy(array('id' => $id));
    }

    /**
     * Finds all threads.
     *
     * @return array of Question
     */
    public function findAllAnswers()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewAnswer(Answer $answer)

    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($answer);
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
     * @param Answer $answer
     */
    protected function doSaveAnswer(Answer $answer)
    {
        $this->em->persist($answer);
        $this->em->flush();
    }


}