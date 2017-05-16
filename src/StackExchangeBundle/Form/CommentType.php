<?php
/**
 * Created by Lezas.
 * Date: 2017-03-04
 * Time: 20:25
 */

namespace StackExchangeBundle\Form;

use StackExchangeBundle\Model\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{

    /**
     * Configures a Thread form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Body', TextareaType::class, [
            'attr' => [
                'id' => 'soemthign',
                'class' => 'tinymce',
                'data-theme' => 'stackExchange_comment'
            ]
        ]);
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Comment::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'stack_exchange_comment';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}