<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 5/4/17
 * Time: 9:31 PM
 */

namespace ELearning\CompanyPortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegistrationController extends Controller
{
    public function indexAction()
    {
        return $this->render('PortalBundle:Registration:index.html.twig');
    }
}