<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder
            ->add('photo', FileType::class, array('label' => 'Your Foto', 'mapped' => false, 'required' => false))
            ->add('description', TextType::class, array('label' => 'Description', 'required' => false));

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'main_user_profile';
    }
}