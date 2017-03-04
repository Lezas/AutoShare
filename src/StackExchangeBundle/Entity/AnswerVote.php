<?php

namespace StackExchangeBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use MyAutoBundle\Entity\User;
use StackExchangeBundle\Model\Vote as BaseVote;

/**
 * @ORM\Entity
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
     * @ORM\Column(type="integer")
     */
    protected $value;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Comment of this vote
     *
     * @var Answer
     * @ORM\ManyToOne(targetEntity="StackExchangeBundle\Entity\Answer")
     */
    protected $object;

    /**
     * Comment of this vote
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="MyAutoBundle\Entity\User")
     */
    protected $user;

    public function setAnswer(Answer $answer)
    {
        $this->setObject($answer);
    }

    public function getAnswer()
    {
        return $this->getObject();
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Checks if the value is an appropriate one.
     *
     * @param mixed $value
     *
     * @return boolean True, if the integer representation of the value is not null or 0.
     */
    protected function checkValue($value)
    {
        return null !== $value && intval($value);
    }

}