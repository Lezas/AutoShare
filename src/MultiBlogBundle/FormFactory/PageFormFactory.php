<?php

namespace MultiBlogBundle\FormFactory;


use MultiBlogBundle\Form\PageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class PageFormFactory extends AbstractType
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
        $builder = $this->formFactory->createBuilder(PageType::class);

        if ($action !== null) {
            $builder->setAction($action);
        }

        return $builder->getForm();
    }
}