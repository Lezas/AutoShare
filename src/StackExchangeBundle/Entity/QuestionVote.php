<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-11
 * Time: 20:57
 */

namespace StackExchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use StackExchangeBundle\Model\BaseVote;

//use FOS\CommentBundle\Entity\Vote as BaseVote;


/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class QuestionVote extends BaseVote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Comment of this vote
     *
     * @var Answer
     * @ORM\ManyToOne(targetEntity="StackExchangeBundle\Entity\Answer")
     */
    protected $object;

    /**
     * @param Question $question
     */
    public function setQuestion(Question $question)
    {
        $this->setObject($question);
    }

    /**
     * @return \StackExchangeBundle\Model\VotableInterface
     */
    public function getQuestion()
    {
        return $this->getObject();
    }
}