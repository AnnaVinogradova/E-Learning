<?php

namespace ELearning\CompanyPortalBundle\Controller;

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
     * Lists all Flow entities.
     *
     * @Route("/", name="flow_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $flows = $em->getRepository('PortalBundle:Flow')->findAll();

        return $this->render('flow/index.html.twig', array(
            'flows' => $flows,
        ));
    }

    /**
     * Creates a new Flow entity.
     *
     * @Route("/new", name="flow_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $flow = new Flow();
        $form = $this->createForm(new FlowType(), $flow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flow);
            $em->flush();

            return $this->redirectToRoute('flow_show', array('id' => $flow->getId()));
        }

        return $this->render('flow/new.html.twig', array(
            'flow' => $flow,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Flow entity.
     *
     * @Route("/{id}", name="flow_show")
     * @Method("GET")
     */
    public function showAction(Flow $flow)
    {
        $deleteForm = $this->createDeleteForm($flow);

        return $this->render('flow/show.html.twig', array(
            'flow' => $flow,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Flow entity.
     *
     * @Route("/{id}/edit", name="flow_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Flow $flow)
    {
        $deleteForm = $this->createDeleteForm($flow);
        $editForm = $this->createForm(new FlowType(), $flow);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flow);
            $em->flush();

            return $this->redirectToRoute('flow_edit', array('id' => $flow->getId()));
        }

        return $this->render('flow/edit.html.twig', array(
            'flow' => $flow,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Flow entity.
     *
     * @Route("/{id}", name="flow_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Flow $flow)
    {
        $form = $this->createDeleteForm($flow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($flow);
            $em->flush();
        }

        return $this->redirectToRoute('flow_index');
    }

    /**
     * Creates a form to delete a Flow entity.
     *
     * @param Flow $flow The Flow entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Flow $flow)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('flow_delete', array('id' => $flow->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
