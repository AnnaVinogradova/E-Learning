<?php

namespace ELearning\CompanyPortalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\Vacancy;
use ELearning\CompanyPortalBundle\Form\VacancyType;

/**
 * Vacancy controller.
 *
 * @Route("/company-panel/vacancy")
 */
class VacancyController extends Controller
{
    /**
     * Lists all Vacancy entities.
     *
     * @Route("/", name="vacancy_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $vacancies = $em->getRepository('PortalBundle:Vacancy')->findAll();

        return $this->render('vacancy/index.html.twig', array(
            'vacancies' => $vacancies,
        ));
    }

    /**
     * Creates a new Vacancy entity.
     *
     * @Route("/new", name="vacancy_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user= $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('PortalBundle:Company')->findOneByOwner($user);

        $vacancy = new Vacancy();
        $form = $this->createForm(new VacancyType(), $vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vacancy->setCompany($company);
            $em->persist($vacancy);
            $em->flush();

            return $this->redirectToRoute('vacancy_show', array('id' => $vacancy->getId()));
        }

        return $this->render('vacancy/new.html.twig', array(
            'vacancy' => $vacancy,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Vacancy entity.
     *
     * @Route("/{id}", name="vacancy_show")
     * @Method("GET")
     */
    public function showAction(Vacancy $vacancy)
    {
        $deleteForm = $this->createDeleteForm($vacancy);

        return $this->render('vacancy/show.html.twig', array(
            'vacancy' => $vacancy,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Vacancy entity.
     *
     * @Route("/{id}/edit", name="vacancy_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vacancy $vacancy)
    {
        $deleteForm = $this->createDeleteForm($vacancy);
        $editForm = $this->createForm(new VacancyType(), $vacancy);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vacancy);
            $em->flush();

            return $this->redirectToRoute('vacancy_edit', array('id' => $vacancy->getId()));
        }

        return $this->render('vacancy/edit.html.twig', array(
            'vacancy' => $vacancy,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Vacancy entity.
     *
     * @Route("/{id}", name="vacancy_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Vacancy $vacancy)
    {
        $form = $this->createDeleteForm($vacancy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vacancy);
            $em->flush();
        }

        return $this->redirectToRoute('vacancy_index');
    }

    /**
     * Creates a form to delete a Vacancy entity.
     *
     * @param Vacancy $vacancy The Vacancy entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vacancy $vacancy)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vacancy_delete', array('id' => $vacancy->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
