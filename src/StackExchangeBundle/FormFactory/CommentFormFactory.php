<?php

namespace StackExchangeBundle\FormFactory;

use StackExchangeBundle\Form\CommentType;
use StackExchangeBundle\Model\FormFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class CommentFormFactory extends FormFactory
{
    protected $class = CommentType::class;
}