<?php

namespace StackExchangeBundle\Form;

use StackExchangeBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
{

    /**
     * Configures a Thread form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['label' => 'Title'])
            ->add('text', TextareaType::class, [
                'label' => 'Question',
                'required' => true,
                'attr' => [
                    'class' => 'tinymce',
                    'data-theme' => 'advanced'
                ]

            ])
            ->add('tags', TextType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'tag_field'
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Save']);
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
            'data_class' => Question::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'stack_exchange_question';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}