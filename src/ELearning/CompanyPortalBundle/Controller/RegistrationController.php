<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 5/4/17
 * Time: 9:31 PM
 */

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Company;
use ELearning\CompanyPortalBundle\Entity\Teacher;
use ELearning\CompanyPortalBundle\Form\CompanyRegistrationType;
use ELearning\CompanyPortalBundle\Form\ConfirmationType;
use ELearning\CompanyPortalBundle\Form\TeacherRegistrationType;
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

    public function inviteTeacherAction(Request $request)
    {
        $form = $this->createForm(TeacherRegistrationType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $userManager = $this->get('fos_user.user_manager');
            $email_exist = $userManager->findUserByEmail($data["email"]);
            $username_exist = $userManager->findUserByUsername($data["email"]);
            if(!$email_exist && !$username_exist){
                $user = $userManager->createUser();
                $user->setUsername($data["email"]);
                $user->setEmail($data["email"]);
                $user->setEmailCanonical($data["email"]);
                $user->setEnabled(0);
                $user->setPlainPassword('  ');
                $user->addRole("ROLE_TEACHER");

                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $token = $tokenGenerator->generateToken();
                $user->setConfirmationToken($token);

                $userManager->updateUser($user);

                $teacher = new Teacher();
                $teacher->setUser($user);
                $teacher->setContacts($data["contacts"]);
                $teacher->setExperience($data["experience"]);
                $teacher->setFullName($data["full_name"]);
                $teacher->setQualification($data["qualification"]);
                $teacher->setCompany($this->getCompany());

                $em = $this->getDoctrine()->getManager();
                $em->persist($teacher);
                $em->flush();
            } else {
                $form->addError(new FormError('User with the same email already exists'));
            }
        }

        return $this->render('PortalBundle:Registration:inviteTeacher.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function confirmRegistrationAction(Request $request, $token)
    {
        $form = $this->createForm(ConfirmationType::class, null, array(
            'token' => $token
        ));
        $form->handleRequest($request);
        $userManager = $this->get('fos_user.user_manager');

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userManager->findUserBy(array('confirmationToken' => $data['invite_token']));
            $user->setPlainPassword($data['password']);
            $user->setEnabled(1);
            $user->setConfirmationToken(null);

            $userManager->updateUser($user);
        }

        $user = $userManager->findUserBy(array('confirmationToken' => $token));

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('PortalBundle:Registration:confirmRegistration.html.twig', array(
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

    private function getCompany()
    {
        $user= $this->get('security.context')->getToken()->getUser();
        if (! $this->get('security.context')->isGranted('ROLE_COMPANY')) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('PortalBundle:Company')->findOneByOwner($user);

        return $company;
    }
}