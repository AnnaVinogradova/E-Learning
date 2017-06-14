<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\Homework;
use ELearning\CompanyPortalBundle\Form\HomeworkType;

/**
 * Homework controller.
 *
 * @Route("/homework")
 */
class HomeworkController extends Controller
{
    /**
     * Lists all Homework entities.
     *
     * @Route("/", name="homework_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $homeworks = $em->getRepository('PortalBundle:Homework')->findAll();

        return $this->render('homework/index.html.twig', array(
            'homeworks' => $homeworks,
        ));
    }

    /**
     * Creates a new Homework entity.
     *
     * @Route("/new/{id}", name="homework_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Course $course)
    {
        if ($this->getCompany() != $course->getCompany()) {
            throw $this->createAccessDeniedException();
        }
        $homework = new Homework();
        $form = $this->createForm(new HomeworkType(), $homework);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $homework->setCourse($course);
            $em->persist($homework);
            $em->flush();

            return $this->redirectToRoute('course_edit', array('id' => $course->getId()));
        }

        return $this->render('homework/new.html.twig', array(
            'homework' => $homework,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Homework entity.
     *
     * @Route("/{id}", name="homework_show")
     * @Method("GET")
     */
    public function showAction(Homework $homework)
    {
        $deleteForm = $this->createDeleteForm($homework);

        return $this->render('homework/show.html.twig', array(
            'homework' => $homework,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Homework entity.
     *
     * @Route("/{id}/edit", name="homework_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Homework $homework)
    {
        if ($this->getCompany() != $homework->getCourse()->getCompany()) {
            throw $this->createAccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($homework);
        $editForm = $this->createForm(new HomeworkType(), $homework);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($homework);
            $em->flush();

            return $this->redirectToRoute('course_edit', array('id' => $homework->getCourse()->getId()));
        }

        return $this->render('homework/edit.html.twig', array(
            'homework' => $homework,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Homework entity.
     *
     * @Route("/{id}", name="homework_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Homework $homework)
    {
        $course = $homework->getCourse();
        if ($this->getCompany() != $course->getCompany()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createDeleteForm($homework);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($homework);
            $em->flush();
        }

        return $this->redirectToRoute('course_edit', array('id' => $course->getId()));
    }

    /**
     * Creates a form to delete a Homework entity.
     *
     * @param Homework $homework The Homework entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Homework $homework)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('homework_delete', array('id' => $homework->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
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
