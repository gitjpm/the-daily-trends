<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FeedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array("required" => "required", "attr" => array("class" => "form-control")))
            ->add('body', TextareaType::class, array("required" => "required", "attr" => array("class" => "form-control")))
            ->add('image', TextType::class, array("required" => "required", "attr" => array("class" => "form-control")))
            ->add('source', TextType::class, array("required" => "required", "attr" => array("class" => "form-control")))
            ->add('publisher', TextType::class, array("required" => "required", "attr" => array("class" => "form-control")))
            ->add('Guardar', SubmitType::class, array("attr" => array("class" => "btn btn-md btn-outline-success")));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Feed'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_feed';
    }


}
