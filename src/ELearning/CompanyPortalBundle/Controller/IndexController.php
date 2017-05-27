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
        } elseif ($this->get('security.context')->isGranted('ROLE_TEACHER')) {
            return $this->redirectToRoute('teacher_profile');
        }

        return $this->redirectToRoute('fos_user_profile_show');
    }

    public function teacherProfileAction()
    {
        $teacher = $this->getTeacher();
        foreach ($teacher->getCompany()->getCourses() as $course) {
            $homeworks = $course->getHomeworks();
            $ids = array();
            foreach ($homeworks as $homework) {
                $ids[] = $homework->getId();
            }

            $em = $this->getDoctrine()->getManager();
            $course->activeHomeworks = $em->getRepository('PortalBundle:UserHomework')->getActiveHomeworks($ids);
            $course->activeExams = $em->getRepository('PortalBundle:UserExam')->getActiveExams($course->getExam());
        }
        return $this->render('PortalBundle:Index:teacherProfile.html.twig', array(
            'teacher' => $teacher
        ));
    }

    private function getTeacher()
    {
        $user= $this->get('security.context')->getToken()->getUser();
        if (! $this->get('security.context')->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $teacher = $em->getRepository('PortalBundle:Teacher')->findOneByUser($user);

        return $teacher;
    }
}