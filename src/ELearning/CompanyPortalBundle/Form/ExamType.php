<?php

namespace ELearning\CompanyPortalBundle\Form;

use ELearning\CompanyPortalBundle\Entity\ExamQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('questions', CollectionType::class, array(
                'entry_type' => ExamQuestionType::class,
                'allow_add'    => true,
            ));
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ELearning\CompanyPortalBundle\Entity\Exam'
        ));
    }
}
