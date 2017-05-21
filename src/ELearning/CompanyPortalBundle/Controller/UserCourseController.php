<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use ELearning\CompanyPortalBundle\Entity\Flow;
use ELearning\CompanyPortalBundle\Entity\UserRequest;
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
     * @Route("/{id}", name="manage-requests")
     * @Method("GET")
     */
    public function indexAction(Course $course)
    {
        $em = $this->getDoctrine()->getManager();

        $openFlow = $em->getRepository('PortalBundle:Flow')->getOpenFlow($course);
        if ($openFlow) {
            $openFlow = $openFlow[0];
        }

        foreach ($openFlow->getRequests() as $request) {
            $request->form = $this->createDeleteForm($request)->createView();
        }

        return $this->render('usercourse/index.html.twig', array(
            'userRequests' => $openFlow->getRequests(),
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
     * Creates a new UserCourse for free course.
     *
     * @Route("/new/{id}/request", name="user_request_new")
     * @Method({"GET"})
     */
    public function newRequestAction(Request $request, Flow $flow)
    {
        $em = $this->getDoctrine()->getManager();
        $userCourse = $em->getRepository('PortalBundle:UserCourse')->findOneBy(array(
            'user' => $this->get('security.context')->getToken()->getUser(),
            'course' => $flow->getCourse()
        ));

        $userRequest = $em->getRepository('PortalBundle:UserRequest')->findOneBy(array(
            'user' => $this->get('security.context')->getToken()->getUser(),
            'flow' => $flow
        ));

        if (! $userCourse && ! $userRequest) {
            $userRequest = new UserRequest();
            $userRequest->setUser($this->get('security.context')->getToken()->getUser());
            $userRequest->setFlow($flow);
            $em->persist($userRequest);
            $em->flush();
        }
        return $this->redirectToRoute('courses_public');
    }

    /**
     * Finds and displays a UserCourse entity.
     *
     * @Route("/{id}/accept", name="request_accept")
     * @Method("GET")
     */
    public function acceptAction(UserRequest $userRequest)
    {
        $userCourse = new UserCourse();
        $userCourse->setUser($userRequest->getUser());
        $userCourse->setCourse($userRequest->getFlow()->getCourse());
        $userCourse->setFlow($userRequest->getFlow());

        $em = $this->getDoctrine()->getManager();
        $em->persist($userCourse);
        $em->remove($userRequest);
        $em->flush();

        return $this->redirectToRoute('manage-requests', array(
            'id' => $userCourse->getCourse()->getId()
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
     * Deletes a UserRequest entity.
     *
     * @Route("/{id}", name="userrequest_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserRequest $userRequest)
    {
        $course = $userRequest->getFlow()->getCourse();
        $form = $this->createDeleteForm($userRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userRequest);
            $em->flush();
        }

        return $this->redirectToRoute('manage-requests',
            array(
                'id' => $course->getId()
            ));
    }

    /**
     * Creates a form to delete a UserRequest entity.
     *
     * @param UserRequest $userRequest The UserRequest entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserRequest $userRequest)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userrequest_delete', array('id' => $userRequest->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
