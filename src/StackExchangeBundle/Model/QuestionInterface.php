<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-15
 * Time: 10:58
 */

namespace StackExchangeBundle\Model;


interface QuestionInterface
{
    public function getTitle();


    public function setTitle();


    public function setText();

    public function getText();
}