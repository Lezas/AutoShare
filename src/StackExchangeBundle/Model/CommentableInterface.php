<?php
/**
 * Created by Lezas.
 * Date: 2017-03-04
 * Time: 19:44
 */

namespace StackExchangeBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;

interface CommentableInterface
{
    /**
     * @param CommentInterface $comment
     */
    public function addComment(CommentInterface $comment);
    /**
     * @param CommentInterface $comment
     */
    public function removeComment(CommentInterface $comment);

    /**
     * @param CommentInterface $comment
     * @return bool
     */
    public function hasComment(CommentInterface $comment);

    /**
     * @return ArrayCollection|CommentInterface[]
     */
    public function getComments();

}