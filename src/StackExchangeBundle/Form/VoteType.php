<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-25
 * Time: 20:16
 */

namespace StackExchangeBundle\Form;


use StackExchangeBundle\Model\Vote;
use Symfony\Component\Form\AbstractType;
use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Entity\Question;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    /**
     * Configures a Thread form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value',TextType::class);
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
            'data_class' => Vote::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'stack_exchange_vote';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}