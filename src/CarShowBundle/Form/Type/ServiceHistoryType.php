<?php
// DAWeldonExampleBundle/Form/Type/ProfileFormType.php
namespace CarShowBundle\Form\Type;

use CarShowBundle\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceHistoryType extends AbstractType
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

        // add your custom field
        $builder
            ->add('mileage', NumberType::class, ['label' => 'Mileage'])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('text', TextareaType::class, ['label' => 'Informacija', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Saugoti']);
    }

    public function getName()
    {
        return 'serviceHistory';
    }
    public function setDefaultOptions(OptionsResolver  $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CarShowBundle/Entity/ServiceHistory'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null
        ]);
    }
}