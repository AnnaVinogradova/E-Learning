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

class TeacherRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('full_name', 'text')
            ->add('qualification', 'text')
            ->add('experience', 'text')
            ->add('contacts', 'text')
        ;
    }
}