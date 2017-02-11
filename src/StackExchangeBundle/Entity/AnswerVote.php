<?php
namespace StackExchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use StackExchangeBundle\Model\BaseVote;

//use FOS\CommentBundle\Entity\Vote as BaseVote;



/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class AnswerVote extends BaseVote
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

    public function setAnswer(Answer $answer)
    {
        $this->setObject($answer);
    }

    public function getAnswer()
    {
        return $this->getObject();
    }

}