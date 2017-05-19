<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Category;
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
     * Lists all Vacancy entities for company.
     *
     * @Route("/", name="vacancy_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $company = $this->getCompany();
        foreach ($company->getVacancies() as $vacancy) {
            $vacancy->form = $this->createDeleteForm($vacancy)->createView();
        }

        return $this->render('vacancy/index.html.twig', array(
            'company' => $company,
        ));
    }

    /**
     * Lists all Vacancies entities.
     *
     * @Route("/list", name="vacancy_public")
     * @Method("GET")
     */
    public function publicListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $vacancies = $em->getRepository('PortalBundle:Vacancy')->findAll();
        $categories = $em->getRepository('PortalBundle:Category')->findAll();

        return $this->render('vacancy/publicList.html.twig', array(
            'vacancies' => $vacancies,
            'categories' => $categories
        ));
    }

    /**
     * Lists Vacancies entities by category.
     *
     * @Route("/search/{id}", name="vacancy_search")
     * @Method("GET")
     */
    public function findAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $vacancies = $em->getRepository('PortalBundle:Vacancy')->getVacanciesByCategory($category);
        $categories = $em->getRepository('PortalBundle:Category')->findAll();

        return $this->render('vacancy/publicList.html.twig', array(
            'vacancies' => $vacancies,
            'categories' => $categories,
            'selected' => $category
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
        $em = $this->getDoctrine()->getManager();
        $company = $this->getCompany();

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
        return $this->render('vacancy/show.html.twig', array(
            'vacancy' => $vacancy,
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
        if ($this->getCompany() != $vacancy->getCompany()) {
            throw $this->createAccessDeniedException();
        }

        $editForm = $this->createForm(new VacancyType(), $vacancy);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vacancy);
            $em->flush();

            return $this->redirectToRoute('vacancy_index');
        }

        return $this->render('vacancy/edit.html.twig', array(
            'vacancy' => $vacancy,
            'edit_form' => $editForm->createView(),
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
        if ($this->getCompany() != $vacancy->getCompany()) {
            throw $this->createAccessDeniedException();
        }

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
