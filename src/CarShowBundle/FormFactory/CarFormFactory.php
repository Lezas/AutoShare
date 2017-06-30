<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-12
 * Time: 19:06
 */

namespace CarShowBundle\FormFactory;


use Application\Sonata\MediaBundle\Entity\Media;
use CarShowBundle\Entity\Car;
use CarShowBundle\Form\Type\AutoType;
use CarShowBundle\Form\Type\PictureSelectType;

use StackExchangeBundle\Form\AnswerType;
use StackExchangeBundle\Form\QuestionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class CarFormFactory extends AbstractType
{

    protected $class = AutoType::class;
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
     * @param $type
     * @param string $name
     */
    public function __construct(FormFactoryInterface $formFactory, $type, $name)
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
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