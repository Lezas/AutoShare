<?php

namespace StackExchangeBundle\Form;

use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-12
 * Time: 19:20
 */
class AnswerType extends AbstractType
{

    /**
     * Configures a Thread form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'tinymce',
                    'data-theme' => 'advanced'
                ]

            ])
            ->add('submit', SubmitType::class, ['label' => 'Submit']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Answer::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'stack_exchange_answer';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}