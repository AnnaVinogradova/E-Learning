<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Homework;
use ELearning\CompanyPortalBundle\Entity\Material;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\Course;
use ELearning\CompanyPortalBundle\Form\CourseType;

/**
 * Course controller.
 *
 * @Route("/course")
 */
class CourseController extends Controller
{
    /**
     * Lists all Course entities by company.
     *
     * @Route("/", name="course_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $company = $this->getCompany();
        foreach ($company->getCourses() as $course) {
            $course->form = $this->createDeleteForm($course)->createView();
        }

        return $this->render('course/index.html.twig', array(
            'company' => $company,
        ));
    }

    /**
     * Lists all Courses entities.
     *
     * @Route("/list", name="courses_public")
     * @Method("GET")
     */
    public function publicListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $courses = $em->getRepository('PortalBundle:Course')->findAll();

        return $this->render('course/publicList.html.twig', array(
            'courses' => $courses,
        ));
    }

    /**
     * Creates a new Course entity.
     *
     * @Route("/new", name="course_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $this->getCompany();

        $course = new Course();
        $form = $this->createForm(new CourseType(), $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $course->getImg();

            if ($file == null) {
                $fileName = 'default.jpg';
            } else {
                $fileName = $this->saveLogo($file);
            }
            $course->setImg($fileName);
            $course->setCompany($company);

            $em->persist($course);
            $em->flush();

            return $this->redirectToRoute('course_show', array('id' => $course->getId()));
        }

        return $this->render('course/new.html.twig', array(
            'course' => $course,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Course entity.
     *
     * @Route("/{id}", name="course_show")
     * @Method("GET")
     */
    public function showAction(Course $course)
    {
        return $this->render('course/show.html.twig', array(
            'course' => $course,
        ));
    }

    /**
     * Displays a form to edit an existing Course entity.
     *
     * @Route("/{id}/edit", name="course_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Course $course)
    {
        if ($this->getCompany() != $course->getCompany()) {
            throw $this->createAccessDeniedException();
        }

        $fileName = $course->getImg();
        $deleteForm = $this->createDeleteForm($course);
        $editForm = $this->createForm(new CourseType(), $course);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $file = $course->getImg();
            if($file != null){
                $fileName = $this->saveLogo($file);
            }

            $course->setImg($fileName);
            $em->persist($course);
            $em->flush();

            return $this->redirectToRoute('course_index');
        }

        foreach ($course->getMaterials() as $material) {
            $material->ext = pathinfo($material->getPath(), PATHINFO_EXTENSION);
            $material->form = $this->createDeleteMaterialForm($material)->createView();
        }

        foreach ($course->getHomeworks() as $homework) {
            $homework->form = $this->createDeleteHomeworkForm($homework)->createView();
        }

        return $this->render('course/edit.html.twig', array(
            'course' => $course,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Course entity.
     *
     * @Route("/{id}", name="course_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Course $course)
    {
        if ($this->getCompany() != $course->getCompany()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createDeleteForm($course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($course);
            $em->flush();
        }

        return $this->redirectToRoute('course_index');
    }

    /**
     * Course entity for student.
     *
     * @Route("/learn/{id}", name="course_for_student")
     * @Method("GET")
     */
    public function courseForStudentAction(Course $course)
    {
        return $this->render('course/courseForStudent.html.twig', array(
            'course' => $course
        ));
    }

    /**
     * Course entity for student.
     *
     * @Route("/learn/list/courses", name="user_courses")
     * @Method("GET")
     */
    public function userCoursesAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $courses = $em->getRepository('PortalBundle:Course')->findAll();
        return $this->render('course/userCourses.html.twig', array(
            'courses' => $courses
        ));
    }

    /**
     * Creates a form to delete a Course entity.
     *
     * @param Course $course The Course entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Course $course)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('course_delete', array('id' => $course->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to delete a Material entity.
     *
     * @param Material $material The Material entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteMaterialForm(Material $material)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('material_delete', array('id' => $material->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Creates a form to delete a Homework entity.
     *
     * @param Homework $homework The Homework entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteHomeworkForm(Homework $homework)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('homework_delete', array('id' => $homework->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Save logo jpeg file
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @return string
     */
    private function saveLogo($file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('company_course_directory'),
            $fileName
        );
        return $fileName;
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
