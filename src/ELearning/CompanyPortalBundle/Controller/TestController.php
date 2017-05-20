<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\Test;
use ELearning\CompanyPortalBundle\Form\TestType;

/**
 * Test controller.
 *
 * @Route("/test")
 */
class TestController extends Controller
{
    /**
     * Lists all Test entities.
     *
     * @Route("/", name="test_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tests = $em->getRepository('PortalBundle:Test')->findAll();

        return $this->render('test/index.html.twig', array(
            'tests' => $tests,
        ));
    }

    /**
     * Creates a new Test entity.
     *
     * @Route("/new/{id}", name="test_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Course $course)
    {
        $test = new Test();
        $form = $this->createForm(new TestType(), $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $test->setCourse($course);
            $em->persist($test);
            $em->flush();

            return $this->redirectToRoute('course_edit', array('id' => $course->getId()));
        }

        return $this->render('test/new.html.twig', array(
            'test' => $test,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Test entity.
     *
     * @Route("/{id}", name="test_show")
     * @Method("GET")
     */
    public function showAction(Test $test)
    {
        $deleteForm = $this->createDeleteForm($test);

        return $this->render('test/show.html.twig', array(
            'test' => $test,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Test entity.
     *
     * @Route("/{id}/edit", name="test_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Test $test)
    {
        $deleteForm = $this->createDeleteForm($test);
        $editForm = $this->createForm(new TestType(), $test);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($test);
            $em->flush();

            return $this->redirectToRoute('test_edit', array('id' => $test->getId()));
        }

        return $this->render('test/edit.html.twig', array(
            'test' => $test,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Test entity.
     *
     * @Route("/{id}", name="test_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Test $test)
    {
        $form = $this->createDeleteForm($test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($test);
            $em->flush();
        }

        return $this->redirectToRoute('test_index');
    }

    /**
     * Creates a form to delete a Test entity.
     *
     * @param Test $test The Test entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Test $test)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('test_delete', array('id' => $test->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
