<?php


namespace MultiBlogBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title', TextType::class, ['label' => 'Title'])
            ->add('body', TextareaType::class, [
                'label' => 'Question',
                'required' => true,
                'attr' => [
                    'class' => 'tinymce',
                    'data-theme' => 'advanced'
                ]

            ])
            ->add('submit', SubmitType::class, ['label' => 'Save']);

    }
    public function getName()
    {
        return 'multi_blog_post';
    }
    public function setDefaultOptions(OptionsResolver  $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MultiBlogBundle\Entity\Post'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }
}