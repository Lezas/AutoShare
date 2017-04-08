<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-12
 * Time: 19:06
 */

namespace CarShowBundle\FormFactory;


use Application\Sonata\MediaBundle\Entity\Media;
use CarShowBundle\Form\Type\PictureSelectType;
use StackExchangeBundle\Form\AnswerType;
use StackExchangeBundle\Form\QuestionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class PictureSelectFormFactory extends AbstractType
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



    protected $serviceContainer;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param string $type
     * @param string $name
     */
    public function __construct(FormFactoryInterface $formFactory, $type, $name, $serviceContainer)
    {
        $this->formFactory = $formFactory;
        $this->type = $type;
        $this->name = $name;
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Creates a thread form
     *
     * @param Media[] $pictures
     * @return FormInterface
     */
    public function createForm($car, $pictures = null)
    {
        $data = [];

        foreach ($pictures as $picture) {
            $provider = $this->serviceContainer->get($picture->getProviderName());

            $url = $provider->generatePublicUrl($picture, 'default_small');
            $data[]= $url;
        }

        $builder = $this->formFactory->createBuilder(PictureSelectType::class, $car, ['media' => $pictures, 'pictures_url' => $data]);

        return $builder->getForm();
    }
}