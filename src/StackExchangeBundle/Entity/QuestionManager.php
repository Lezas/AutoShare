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
     * Finds one comment thread by the given criteria
     *
     * @param  array           $criteria
     * @return Question
     */
    public function findQuestionBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findQuestionsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string          $id
     * @return Question
     *
     */
    public function findQuestionById($id)
    {
        return $this->findQuestionBy(array('id' => $id));
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

}