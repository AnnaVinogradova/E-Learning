<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 5/4/17
 * Time: 9:31 PM
 */

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Form\CompanyRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    public function indexAction()
    {
        return $this->render('PortalBundle:Registration:index.html.twig');
    }

    public function registerAction(Request $request)
    {
        $form = $this->createForm(CompanyRegistrationType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $userManager = $this->get('fos_user.user_manager');
            $email_exist = $userManager->findUserByEmail($data["email"]);
            $username_exist = $userManager->findUserByUsername($data["username"]);
            if(!$email_exist && !$username_exist){
                $user = $userManager->createUser();
                $user->setUsername($data["username"]);
                $user->setEmail($data["email"]);
                $user->setEmailCanonical($data["email"]);
                $user->setEnabled(1);
                $user->setPlainPassword($data["password"]);
                $userManager->updateUser($user);
            }
        }

        return $this->render('PortalBundle:Registration:register.html.twig', array(
            'form' => $form->createView()
    ));
    }
}