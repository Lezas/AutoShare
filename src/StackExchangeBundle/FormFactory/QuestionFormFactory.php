<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-12
 * Time: 19:06
 */

namespace StackExchangeBundle\FormFactory;


use StackExchangeBundle\Form\QuestionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class QuestionFormFactory extends AbstractType
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param string               $type
     * @param string               $name
     */
    public function __construct(FormFactoryInterface $formFactory, $type, $name)
    {
        $this->formFactory = $formFactory;
        $this->type        = $type;
        $this->name        = $name;
    }

    /**
     * Creates a thread form
     *
     * @return FormInterface
     */
    public function createForm()
    {
        $builder = $this->formFactory->createBuilder(QuestionType::class);

        return $builder->getForm();
    }
}