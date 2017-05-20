<?php

namespace ELearning\CompanyPortalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassQuestionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();

        foreach ($options['question']->getAnswers() as $answer) {
            $choices[$answer->getId()] = $answer->getText();
        }

        $builder->add('answers', ChoiceType::class, array(
            'choices'  => $choices,
            'multiple' => true,
            'expanded' => "true"));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'question' => null,
        ));
    }
}
