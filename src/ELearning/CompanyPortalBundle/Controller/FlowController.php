<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\Flow;
use ELearning\CompanyPortalBundle\Form\FlowType;

/**
 * Flow controller.
 *
 * @Route("/flow")
 */
class FlowController extends Controller
{

    /**
     * Creates a new Flow entity.
     *
     * @Route("/new/{id}", name="flow_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Course $course)
    {
        if ($this->getCompany() != $course->getCompany()) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();

        $openFlow = $em->getRepository('PortalBundle:Flow')->getOpenFlow($course);

        if ($openFlow) {
            return $this->render('flow/alreadyExist.html.twig');
        }

        $flow = new Flow();
        $form = $this->createForm(new FlowType(), $flow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flow->setCourse($course);
            $em->persist($flow);
            $em->flush();

            return $this->redirectToRoute('course_edit', array('id' => $course->getId()));
        }

        return $this->render('flow/new.html.twig', array(
            'flow' => $flow,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Flow entity.
     *
     * @Route("/{id}/edit/", name="flow_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Flow $flow)
    {
        if ($this->getCompany() != $flow->getCourse()->getCompany()) {
            throw $this->createAccessDeniedException();
        }
        $editForm = $this->createForm(new FlowType(), $flow);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flow);
            $em->flush();

            return $this->redirectToRoute('course_edit', array('id' => $flow->getCourse()->getId()));
        }

        return $this->render('flow/edit.html.twig', array(
            'flow' => $flow,
            'edit_form' => $editForm->createView(),
            'course' => $flow->getCourse(),

        ));
    }

    /**
    *
    * @Route("/{id}/chat/view", name="flow_chat")
    */
    public function chatAction(Course $course)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('flow/chat.html.twig', array(
            'flow' => $em->getRepository('PortalBundle:Flow')->getOpenFlow($course)[0],

        ));
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
