<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 5/4/17
 * Time: 9:31 PM
 */

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Company;
use ELearning\CompanyPortalBundle\Form\CompanyRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
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
                $user->addRole("ROLE_COMPANY");
                $userManager->updateUser($user);

                $company = new Company();
                $company->setName($data["name"]);
                $company->setDescription($data["description"]);
                $company->setSite($data["site"]);
                $file = $data["logo"];

                if ($file == null) {
                    $fileName = 'default.jpg';
                } else {
                    $fileName = $this->saveLogo($file);
                }
                $company->setLogo($fileName);
                $company->setOwner($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($company);
                $em->flush();
            } else {
                $form->addError(new FormError('Company user with the same email or name already exists'));
            }
        }

        return $this->render('PortalBundle:Registration:register.html.twig', array(
            'form' => $form->createView()
    ));
    }

    /**
     * Save logo jpeg file
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @return string
     */
    private function saveLogo($file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('company_logo_directory'),
            $fileName
        );
        return $fileName;
    }
}