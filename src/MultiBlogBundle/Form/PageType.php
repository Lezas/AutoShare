<?php


namespace MultiBlogBundle\Form;

use MultiBlogBundle\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PageType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title', TextType::class, ['label' => 'Title'])
            ->add('body', TextareaType::class, [
                'label' => 'Page body',
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
    public function getName()
    {
        return 'multi_blog_page';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class
        ]);
    }
}