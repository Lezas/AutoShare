<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-25
 * Time: 19:57
 */

namespace StackExchangeBundle\FormFactory;


use StackExchangeBundle\Form\VoteType;
use StackExchangeBundle\Model\FormFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class VoteFormFactory extends FormFactory
{
    protected $class = VoteType::class;
}