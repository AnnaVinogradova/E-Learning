<?php

namespace ELearning\CompanyPortalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('img', FileType::class, array(
                'data_class' => null,
                'label' => 'Post (JPG file)',
                'required' => false,
                'empty_data'  => null))
            ->add('price')
            ->add('description')
            ->add('duration')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ELearning\CompanyPortalBundle\Entity\Course'
        ));
    }
}
