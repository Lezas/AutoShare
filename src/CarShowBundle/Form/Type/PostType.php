<?php


namespace CarShowBundle\Form\Type;

use CarShowBundle\Entity\Car;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostType extends AbstractType
{
    /**
     * @var Car
     */
    private $auto;

    public function __construct(Car $auto = null)
    {
        $this->auto = $auto;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->auto = $options['auto'];
        $auto = $this->auto;

        $builder->add('title', TextType::class, ['label' => 'Title'])
            ->add('mileage', NumberType::class, ['label' => 'Mileage'])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('text', CKEditorType::class, [
                'config' => [
                    'filebrowserImageBrowseRoute' => 'admin_sonata_media_media_browser',
                    'filebrowserImageBrowseRouteParameters' => ['car_id' => $auto->getId()]
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Saugoti']);

    }
    public function getName()
    {
        return 'post';
    }
    public function setDefaultOptions(OptionsResolver  $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CarShowBundle/Entity/Post'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'auto' => null
        ]);
    }
}