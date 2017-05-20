<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use ELearning\CompanyPortalBundle\Entity\Test;
use ELearning\CompanyPortalBundle\Form\PassQuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\UserTest;
use ELearning\CompanyPortalBundle\Form\UserTestType;

/**
 * UserTest controller.
 *
 * @Route("/usertest")
 */
class UserTestController extends Controller
{
    /**
     * Lists all UserTest entities.
     *
     * @Route("/", name="usertest_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userTests = $em->getRepository('PortalBundle:UserTest')->findAll();

        return $this->render('usertest/index.html.twig', array(
            'userTests' => $userTests,
        ));
    }

    /**
     * Creates a new UserTest entity.
     *
     * @Route("/new/{id}", name="usertest_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Test $test)
    {
        $em = $this->getDoctrine()->getManager();

        $userTest = $em->getRepository('PortalBundle:UserTest')->findOneBy(array(
            'user' => $this->get('security.context')->getToken()->getUser(),
            'test' => $test
        ));

        if (! $userTest) {
            $userTest = new UserTest();
            $userTest->setPoints(0);
            $userTest->setStep(0);
            $userTest->setUser($this->get('security.context')->getToken()->getUser());
            $userTest->setTest($test);

            $em->persist($userTest);
            $em->flush();
        }

        return $this->redirectToRoute('usertest_show', array('id' => $userTest->getId()));
    }

    /**
     * Finds and displays a UserTest entity.
     *
     * @Route("/{id}", name="usertest_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, UserTest $userTest)
    {
        $test = $userTest->getTest();
        $current = $test->getQuestions()[$userTest->getStep()];

        if (count($test->getQuestions()) == $userTest->getStep()) {
            return $this->render(
                'usertest/finish.html.twig', array(
                    'points' => $userTest->getPoints() * 100 / count($test->getQuestions())
                )
            );
        }

        $form = $this->createForm(PassQuestionType::class, null, array(
            'question' => $current,
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData()["answers"];
            $right = true;

            foreach ($current->getAnswers() as $answer) {

                if ($answer->getStatus() && !in_array($answer->getId(), $data)) {
                    $right = false;
                }
                if (! $answer->getStatus() && in_array($answer->getId(), $data)) {
                    $right = false;
                }
            }
            $em = $this->getDoctrine()->getManager();

            $userTest->setStep($userTest->getStep() + 1);
            if ($right) {
                $userTest->setPoints($userTest->getPoints() + 1);
            }
            $em->persist($userTest);
            $em->flush();

            return $this->redirectToRoute("usertest_show", array(
                'id' => $userTest->getId(),
            ));
        }

        return $this->render('usertest/show.html.twig', array(
            'userTest' => $userTest,
            'current' => $current,
            'form' => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing UserTest entity.
     *
     * @Route("/{id}/edit", name="usertest_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserTest $userTest)
    {
        $deleteForm = $this->createDeleteForm($userTest);
        $editForm = $this->createForm(new UserTestType(), $userTest);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userTest);
            $em->flush();

            return $this->redirectToRoute('usertest_edit', array('id' => $userTest->getId()));
        }

        return $this->render('usertest/edit.html.twig', array(
            'userTest' => $userTest,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserTest entity.
     *
     * @Route("/{id}", name="usertest_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserTest $userTest)
    {
        $form = $this->createDeleteForm($userTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userTest);
            $em->flush();
        }

        return $this->redirectToRoute('usertest_index');
    }

    /**
     * Creates a form to delete a UserTest entity.
     *
     * @param UserTest $userTest The UserTest entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserTest $userTest)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usertest_delete', array('id' => $userTest->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
