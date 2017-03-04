<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-25
 * Time: 19:57
 */

namespace StackExchangeBundle\FormFactory;


use StackExchangeBundle\Form\VoteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class VoteFormFactory extends AbstractType
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
     * @param null $action
     * @return FormInterface
     */
    public function createForm($action = null)
    {
        $builder = $this->formFactory->createBuilder(VoteType::class);

        if ($action !== null) {
            $builder->setAction($action);
        }

        return $builder->getForm();
    }
}