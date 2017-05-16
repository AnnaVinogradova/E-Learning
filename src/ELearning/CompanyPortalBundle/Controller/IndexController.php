<?php

/**
 * Created by PhpStorm.
 * User: anna
 * Date: 5/1/17
 * Time: 3:09 PM
 */
namespace ELearning\CompanyPortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('PortalBundle:Index:index.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('PortalBundle:Index:about.html.twig');
    }

    public function profileAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_COMPANY')) {
            return $this->redirectToRoute('company-profile');
        }
        return $this->redirectToRoute('teacher_profile');
        //return $this->redirectToRoute('fos_user_profile_show');
    }

    public function teacherProfileAction()
    {
        return $this->render('PortalBundle:Index:teacherProfile.html.twig');
    }
}