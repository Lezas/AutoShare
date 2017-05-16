<?php

namespace CarShowBundle\Form\Type;

use CarShowBundle\Entity\Car;
use MainBundle\Entity\User;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoType extends AbstractType
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->user = $options['user'];

        $builder->add('brand', TextType::class, ['label' => 'Brand'])
            ->add('model', TextType::class, ['label' => 'Model'])
            ->add('year', DateType::class, [
                    'widget' => 'single_text',
                ]
            )
            ->add('bodyType', TextType::class, ['label' => 'Body type'])
            ->add('powertrain', TextType::class, ['label' => 'Power train'])
            ->add('engineCapacity', NumberType::class, ['label' => 'engine capacity'])
            ->add('power', NumberType::class, ['label' => 'Power in kilowatts'])
            ->add('fuelType', TextType::class, ['label' => 'fuel Type'])
            ->add('foto', FileType::class, [
                'label' => 'Your car foto', 'required' => false, 'mapped' => false
                ]
            )
            ->add('private', CheckboxType::class, array('required' => false))
            ->add('additionalInfo', TextareaType::class, ['label' => 'Additional Info', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Saugoti']);
    }

    public function getName()
    {
        return 'auto';
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class
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