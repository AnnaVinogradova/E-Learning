<?php

/**
 * Created by PhpStorm.
 * User: anna
 * Date: 5/7/17
 * Time: 12:02 PM
 */
namespace ELearning\CompanyPortalBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('name', 'text')
            ->add('description', TextareaType::class)
            ->add('logo', FileType::class, array(
                'data_class' => null,
                'label' => 'Post (JPG file)',
                'required' => false,
                'empty_data'  => null))
            ->add('site', 'text')
        ;
    }
}