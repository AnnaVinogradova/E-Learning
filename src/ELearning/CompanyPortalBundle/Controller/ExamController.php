<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\Exam;
use ELearning\CompanyPortalBundle\Form\ExamType;

/**
 * Exam controller.
 *
 * @Route("/exam")
 */
class ExamController extends Controller
{

    /**
     * Creates a new Exam entity.
     *
     * @Route("/new/{id}", name="exam_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Course $course)
    {
        if ($this->getCompany() != $course->getCompany()) {
            throw $this->createAccessDeniedException();
        }

        $exam = new Exam();
        $form = $this->createForm(new ExamType(), $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $exam->setCourse($course);
            $em->persist($exam);
            $em->flush();

            return $this->redirectToRoute('course_edit', array('id' => $exam->getCourse()->getId()));
        }

        return $this->render('exam/new.html.twig', array(
            'exam' => $exam,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Exam entity.
     *
     * @Route("/{id}/edit", name="exam_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Exam $exam)
    {
        if ($this->getCompany() != $exam->getCourse()->getCompany()) {
            throw $this->createAccessDeniedException();
        }

        $editForm = $this->createForm(new ExamType(), $exam);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($exam);
            foreach ($exam->getQuestions() as $question) {
                $question->setExam($exam);
                $em->persist($question);
            }
            $em->flush();

            return $this->redirectToRoute('course_edit', array('id' => $exam->getCourse()->getId()));
        }

        return $this->render('exam/edit.html.twig', array(
            'exam' => $exam,
            'edit_form' => $editForm->createView(),
        ));
    }

    private function getCompany()
    {
        $user= $this->get('security.context')->getToken()->getUser();
        if (! $this->get('security.context')->isGranted('ROLE_COMPANY') and ! $this->get('security.context')->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('PortalBundle:Company')->findOneByOwner($user);

        if ($this->get('security.context')->isGranted('ROLE_TEACHER')) {
            $teacher = $em->getRepository('PortalBundle:Teacher')->findOneByUser($user);

            $company = $teacher->getCompany();

        }

        return $company;
    }
}
