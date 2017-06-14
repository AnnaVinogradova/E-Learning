<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Homework;
use ELearning\CompanyPortalBundle\Form\CheckHomeworkType;
use ELearning\CompanyPortalBundle\Form\CreateUserHomeworkType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\UserHomework;
use ELearning\CompanyPortalBundle\Form\UserHomeworkType;

/**
 * UserHomework controller.
 *
 * @Route("/userhomework")
 */
class UserHomeworkController extends Controller
{
    /**
     * Lists all UserHomework entities.
     *
     * @Route("/", name="userhomework_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userHomeworks = $em->getRepository('PortalBundle:UserHomework')->findAll();

        return $this->render('userhomework/index.html.twig', array(
            'userHomeworks' => $userHomeworks,
        ));
    }

    /**
     * Creates a new UserHomework entity.
     *
     * @Route("/new/{id}", name="userhomework_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Homework $homework)
    {
        $em = $this->getDoctrine()->getManager();

        $userHomework = $em->getRepository('PortalBundle:UserHomework')->findOneBy(array(
            'user' => $this->get('security.context')->getToken()->getUser(),
            'homework' => $homework
        ));

        if ($userHomework) {
            return $this->redirectToRoute("userhomework_show", array(
                'id' => $userHomework->getId()
            ));
        }

        $userHomework = new UserHomework();
        $form = $this->createForm(new CreateUserHomeworkType(), $userHomework);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userHomework->setUser($this->get('security.context')->getToken()->getUser());
            $userHomework->setHomework($homework);
            $userHomework->setChecked(0);
            $em->persist($userHomework);
            $em->flush();

            return $this->redirectToRoute('userhomework_show', array('id' => $userHomework->getId()));
        }

        return $this->render('userhomework/new.html.twig', array(
            'userHomework' => $userHomework,
            'form' => $form->createView(),
            'tasks' => $homework->getContent()
        ));
    }

    /**
     * Finds and displays a UserHomework entity.
     *
     * @Route("/{id}", name="userhomework_show")
     * @Method("GET")
     */
    public function showAction(UserHomework $userHomework)
    {

        return $this->render('userhomework/show.html.twig', array(
            'userHomework' => $userHomework
        ));
    }

    /**
     * Displays a form to edit an existing UserHomework entity.
     *
     * @Route("/{id}/edit", name="userhomework_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserHomework $userHomework)
    {
        $deleteForm = $this->createDeleteForm($userHomework);
        $editForm = $this->createForm(new CheckHomeworkType(), $userHomework);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userHomework->setChecked(1);
            $em->persist($userHomework);
            $em->flush();

            return $this->redirectToRoute('course_for_teacher',
                array(
                    'id' => $userHomework->getHomework()->getCourse()->getId()
                )
            );
        }

        return $this->render('userhomework/edit.html.twig', array(
            'userHomework' => $userHomework,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserHomework entity.
     *
     * @Route("/{id}", name="userhomework_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserHomework $userHomework)
    {
        $form = $this->createDeleteForm($userHomework);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userHomework);
            $em->flush();
        }

        return $this->redirectToRoute('userhomework_index');
    }

    /**
     * Creates a form to delete a UserHomework entity.
     *
     * @param UserHomework $userHomework The UserHomework entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserHomework $userHomework)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userhomework_delete', array('id' => $userHomework->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
