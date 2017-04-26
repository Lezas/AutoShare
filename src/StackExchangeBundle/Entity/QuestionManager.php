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
use StackExchangeBundle\Event\AnswerEvent;
use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Events;
use StackExchangeBundle\Model\QuestionManager as BaseQuestionManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class QuestionManager extends BaseQuestionManager
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
        $this->repository = $em->getRepository(Question::class);

        $metadata = $em->getClassMetadata(Question::class);

        $this->class = $metadata->name;
    }

    /**
     * {@inheritDoc}
     */
    public function findQuestionsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string $id
     * @return Question
     *
     */
    public function findQuestionById($id)
    {
        return $this->findQuestionBy(array('id' => $id));
    }

    /**
     * Finds one comment thread by the given criteria
     *
     * @param  array $criteria
     * @return Question
     */
    public function findQuestionBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findOneWeekQuestions($amount = null)
    {
        $date = (new \DateTime())->modify('-7 day');
        $qb = $this->repository->createQueryBuilder('q')
            ->addSelect('q.createdAt as HIDDEN time')
            ->addSelect('q.title as HIDDEN title')
            ->addSelect('q.score as HIDDEN score')
            ->where($this->repository->createQueryBuilder('q')->expr()->gte('q.createdAt', ':date_from'))
            ->setParameter('date_from',$date)
            ->addOrderBy('q.score');

        if (null != $amount && is_int($amount)) {
            $qb->setMaxResults($amount);
        }

        return $qb->getQuery()->getResult();

    }

    /**
     * Finds all threads.
     *
     * @return array of Question
     */
    public function findAllQuestion()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewQuestion(Question $question)
    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($question);
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

    public function setQuestionToAnswered(Question $question, Answer $answer)
    {
        try {
            if ($question->getAcceptedAnswer() != null) {
                throw new \Exception('Question already has accepted answer');
            }
            if ($question->isAnswered() != false) {
                throw new \Exception('Question already is answered');
            }

            $question->setAcceptedAnswer($answer);

            $Qevent = new QuestionEvent($question);
            $this->dispatcher->dispatch(Events::QUESTION_ANSWER_ACCEPTED, $Qevent);

            $Aevent = new AnswerEvent($answer);
            $this->dispatcher->dispatch(Events::ANSWER_ACCEPTED, $Aevent);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;

    }

    public function updateQuestionTags(Question $question, $tags)
    {
        //TODO

        //get current tags
        // make removed tags list
        //make added tags list
        //remove tags from Question
        //Add tags to question
    }

    public function addTag(Question $question, Tag $tag)
    {
        //TODO

        //check if tag is not already in list
        //add it
        //call tagManager to add question
    }

    public function removeTag(Question $question, Tag $tag)
    {
        //TODO
        //check if tag is on the list
        //remove it
        //call TagManager to remove question from tag
    }

    /**
     * Saves Question
     *
     * @param Question $question
     */
    protected function doSaveQuestion(Question $question)
    {
        $this->em->persist($question);
        $this->em->flush();
    }

    public function findSimilarQuestionsByTags(Question $question, $amount = null)
    {
        $tags = $question->getTags();
        $qb = $this->repository->createQueryBuilder('q')
            ->addSelect('q.createdAt as HIDDEN time')
            ->addSelect('q.title as HIDDEN title')
            ->addSelect('q.score as HIDDEN score')
            ->join('q.tags', 't')
            ->addSelect('t')
            ->where("t in (:tags)")
            ->andWhere('q.id != :id')
            ->setParameter('id',$question->getId())
            ->setParameter('tags',$tags)
            ->addOrderBy('q.createdAt')
        ;

        if (null != $amount && is_int($amount)) {
            $qb->setMaxResults($amount);
        }

        return $qb->getQuery()->getResult();
    }

}