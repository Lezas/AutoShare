<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-12
 * Time: 19:06
 */

namespace StackExchangeBundle\FormFactory;


use StackExchangeBundle\Form\AnswerType;
use StackExchangeBundle\Form\QuestionType;
use StackExchangeBundle\Model\FormFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class AnswerFormFactory extends FormFactory
{
    protected $class = AnswerType::class;

}