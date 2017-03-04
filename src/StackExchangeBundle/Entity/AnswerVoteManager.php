<?php
/**
 * Created by Lezas
 * Date: 2017-02-14
 * Time: 19:03
 */

namespace StackExchangeBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use StackExchangeBundle\Model\AnswerVoteManager as BaseVoteManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use StackExchangeBundle\Model\Vote as BaseVote;
//TODO answerVoteManager and QuestionVoteManager share same logic - move to one abstract class on just class.
class AnswerVoteManager extends BaseVoteManager
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
        $this->repository = $em->getRepository(AnswerVote::class);

        $metadata = $em->getClassMetadata(AnswerVote::class);

        $this->class = $metadata->name;
    }

    /**
     * Finds one comment thread by the given criteria
     *
     * @param  array           $criteria
     * @return Question
     */
    public function findVoteBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findVotesBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string          $id
     * @return Question
     *
     */
    public function findVoteById($id)
    {
        return $this->findVoteBy(array('id' => $id));
    }

    /**
     * Finds all threads.
     *
     * @return array of Question
     */
    public function findAllVotes()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewVote(BaseVote $vote)
    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($vote);
    }

    public function doesUserVoted($user, $question)
    {
        return (bool) $this->repository->findBy(['object' => $question, 'user' => $user]);
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
     * @param BaseVote $vote
     */
    protected function doSaveVote(BaseVote $vote)
    {
        $this->em->persist($vote);
        $this->em->flush();
    }

}