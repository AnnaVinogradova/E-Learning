<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use ELearning\CompanyPortalBundle\Entity\Exam;
use ELearning\CompanyPortalBundle\Entity\ExamAnswer;
use ELearning\CompanyPortalBundle\Form\CheckExamType;
use ELearning\CompanyPortalBundle\Form\ExamAnswerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\UserExam;
use ELearning\CompanyPortalBundle\Form\UserExamType;

/**
 * UserExam controller.
 *
 * @Route("/userexam")
 */
class UserExamController extends Controller
{
    /**
     * Lists all UserExam entities.
     *
     * @Route("/", name="userexam_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userExams = $em->getRepository('PortalBundle:UserExam')->findAll();

        return $this->render('userexam/index.html.twig', array(
            'userExams' => $userExams,
        ));
    }

    /**
     * Creates a new UserExam entity.
     *
     * @Route("/new/{id}", name="userexam_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Exam $exam)
    {
        $em = $this->getDoctrine()->getManager();

        $userExam = $em->getRepository('PortalBundle:UserExam')->findOneBy(array(
            'user' => $this->get('security.context')->getToken()->getUser(),
            'exam' => $exam
        ));

        if (! $userExam) {
            $userExam = new UserExam();
            $userExam->setChecked(0);
            $userExam->setStatus(null);
            $userExam->setExam($exam);
            $userExam->setFinished(0);
            $userExam->setSertificate(0);
            $userExam->setStartDate(null);
            $userExam->setUser($this->get('security.context')->getToken()->getUser());

            $em->persist($userExam);
            $em->flush();
        }

        return $this->redirectToRoute('userexam_show', array('id' => $userExam->getId()));
    }

    /**
     * Finds and displays a UserExam entity.
     *
     * @Route("/{id}", name="userexam_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, UserExam $userExam)
    {
        if ($userExam->getStartDate() == null) {
            $userExam->setStartDate(new \DateTime("now"));
        }

        if ($startTime = $userExam->getStartDate()) {
            $limit = $startTime->modify('+1 hour');
            if ($limit < new \DateTime("now")) {
                $userExam->setFinished(1);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($userExam);
        $em->flush();
        $answer = null;
        $current = 0;
        $currentQuestion = null;

        foreach ($userExam->getExam()->getQuestions() as $question) {
            $currentQuestion = $question;
            $answer = $em->getRepository('PortalBundle:ExamAnswer')->findOneBy(array(
                'userExam' => $userExam,
                'question' => $question
            ));

            if (!$answer) {
                $answer = new ExamAnswer();
                $answer->setFinished(0);
                $answer->setQuestion($question);
                $answer->setUserExam($userExam);
                $em->persist($answer);
                $em->flush();
            }

            if($answer->getFinished() == 0) {
                break;
            }

            $current++;
        }

        if ($answer->getFinished() == 1) {
            $userExam->setFinished(1);
            $em->persist($userExam);
            $em->flush();
        }

        if (! $userExam->getFinished()) {
            $form = $this->createForm(ExamAnswerType::class, null);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $userAnswer = $form->getData()["answer"];
                $answer->setAnswer($userAnswer);
                $answer->setFinished(1);
                $em->persist($answer);
                $em->flush();
                return $this->redirectToRoute("userexam_show", array(
                    'id' => $userExam->getId()
                ));
            }
        } else {
            if ($userExam->getChecked()) {
                return $this->render('userexam/result.html.twig', array(
                    'result' => $userExam->getStatus()
                ));
            }
                return $this->render('userexam/finish.html.twig');
        }

        return $this->render('userexam/show.html.twig', array(
            'userExam' => $form->createView(),
            'current' => $current,
            'question' => $currentQuestion,
            'all_count' => count($userExam->getExam()->getQuestions()),
            'form' =>$form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing UserExam entity.
     *
     * @Route("/{id}/edit", name="userexam_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserExam $userExam)
    {
        $deleteForm = $this->createDeleteForm($userExam);
        $editForm = $this->createForm(new CheckExamType(), $userExam);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userExam->setChecked(1);
            $em->persist($userExam);
            $em->flush();

            return $this->redirectToRoute('course_for_teacher', array('id' => $userExam->getExam()->getCourse()->getId()));
        }

        return $this->render('userexam/edit.html.twig', array(
            'userExam' => $userExam,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserExam entity.
     *
     * @Route("/{id}", name="userexam_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserExam $userExam)
    {
        $form = $this->createDeleteForm($userExam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userExam);
            $em->flush();
        }

        return $this->redirectToRoute('userexam_index');
    }

    /**
     * Creates a form to delete a UserExam entity.
     *
     * @param UserExam $userExam The UserExam entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserExam $userExam)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userexam_delete', array('id' => $userExam->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
