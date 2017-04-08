<?php

namespace CarShowBundle\Form\Type;

use Application\Sonata\MediaBundle\Entity\Media;
use CarShowBundle\Entity\Car;
use MainBundle\Entity\User;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureSelectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        /** @var Media[] $media */
        $media = $options['media'];
        $urls = $options['pictures_url'];

        $attributes = [];

        for ($i=0;$i < count($media);$i++) {
            $attributes[] = [
                'data-img-src' => $urls[$i],
                'data-img-alt' => $media[$i]->getName(),
                'value' => $media[$i]->getId(),
            ];
        }


        $builder->add('mainPhoto', ChoiceType::class, array(
            'mapped' => false,
            'choices' => $media,
            'choice_attr' => $attributes,
            'attr' => [
                'class' => 'imagePicker'
            ]
        ))
            ->add('submit', SubmitType::class, ['label' => 'Save']);
    }

    public function getName()
    {
        return 'picture_select';
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'media' => null,
            'pictures_url' => null,
        ]);
    }
}