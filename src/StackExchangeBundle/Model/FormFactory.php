<?php
/**
 * Created by Lezas.
 * Date: 2017-06-28
 * Time: 21:02
 */

namespace StackExchangeBundle\Model;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

abstract class FormFactory extends AbstractType
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

    protected $class = null;

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
        $builder = $this->formFactory->createBuilder($this->class);

        if ($action !== null) {
            $builder->setAction($action);
        }

        return $builder->getForm();
    }
}