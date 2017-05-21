<?php

/**
 * Created by PhpStorm.
 * User: anna
 * Date: 5/7/17
 * Time: 12:02 PM
 */
namespace ELearning\CompanyPortalBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $token = $options['token'];

        $builder
            ->add('password', PasswordType::class)
            ->add('invite_token', HiddenType::class, array(
                'data' => $token,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'token' => null,
        ));
    }
}