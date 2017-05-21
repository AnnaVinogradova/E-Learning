<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\UserCourse;
use ELearning\CompanyPortalBundle\Form\UserCourseType;

/**
 * UserCourse controller.
 *
 * @Route("/usercourse")
 */
class UserCourseController extends Controller
{
    /**
     * Lists all UserCourse entities.
     *
     * @Route("/", name="usercourse_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userCourses = $em->getRepository('PortalBundle:UserCourse')->findAll();

        return $this->render('usercourse/index.html.twig', array(
            'userCourses' => $userCourses,
        ));
    }

    /**
     * Creates a new UserCourse for free course.
     *
     * @Route("/new/{id}", name="usercourse_new")
     * @Method({"GET"})
     */
    public function newAction(Request $request, Course $course)
    {
        $em = $this->getDoctrine()->getManager();
        $userCourse = $em->getRepository('PortalBundle:UserCourse')->findOneBy(array(
            'user' => $this->get('security.context')->getToken()->getUser(),
            'course' => $course
        ));

        if (! $userCourse) {
            $userCourse = new UserCourse();
            $userCourse->setCourse($course);
            $userCourse->setUser($this->get('security.context')->getToken()->getUser());
            $em->persist($userCourse);
            $em->flush();
        }
            return $this->redirectToRoute('course_for_student', array('id' => $course->getId()));
    }

    /**
     * Finds and displays a UserCourse entity.
     *
     * @Route("/{id}", name="usercourse_show")
     * @Method("GET")
     */
    public function showAction(UserCourse $userCourse)
    {
        $deleteForm = $this->createDeleteForm($userCourse);

        return $this->render('usercourse/show.html.twig', array(
            'userCourse' => $userCourse,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserCourse entity.
     *
     * @Route("/{id}/edit", name="usercourse_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserCourse $userCourse)
    {
        $deleteForm = $this->createDeleteForm($userCourse);
        $editForm = $this->createForm(new UserCourseType(), $userCourse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userCourse);
            $em->flush();

            return $this->redirectToRoute('usercourse_edit', array('id' => $userCourse->getId()));
        }

        return $this->render('usercourse/edit.html.twig', array(
            'userCourse' => $userCourse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserCourse entity.
     *
     * @Route("/{id}", name="usercourse_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserCourse $userCourse)
    {
        $form = $this->createDeleteForm($userCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userCourse);
            $em->flush();
        }

        return $this->redirectToRoute('usercourse_index');
    }

    /**
     * Creates a form to delete a UserCourse entity.
     *
     * @param UserCourse $userCourse The UserCourse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserCourse $userCourse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usercourse_delete', array('id' => $userCourse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
