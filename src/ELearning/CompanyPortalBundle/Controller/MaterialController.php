<?php

namespace ELearning\CompanyPortalBundle\Controller;

use ELearning\CompanyPortalBundle\Entity\Course;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ELearning\CompanyPortalBundle\Entity\Material;
use ELearning\CompanyPortalBundle\Form\MaterialType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Material controller.
 *
 * @Route("/material")
 */
class MaterialController extends Controller
{
    /**
     * Creates a new Material entity.
     *
     * @Route("/new/{id}", name="material_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Course $course)
    {
        if ($this->getCompany() != $course->getCompany()) {
            throw $this->createAccessDeniedException();
        }

        $material = new Material();
        $form = $this->createForm(new MaterialType(), $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $file = $material->getPath();

            if ($file == null) {
                throw $this->createNotFoundException();
            } else {
                $fileName = $this->saveFile($file);
            }
            $material->setPath($fileName);
            $material->setCourse($course);
            $em->persist($material);
            $em->flush();

            return $this->redirectToRoute('course_edit', array('id' => $course->getId()));
        }

        return $this->render('material/new.html.twig', array(
            'material' => $material,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Material entity.
     *
     * @Route("/{id}", name="material_show")
     * @Method("GET")
     */
    public function showAction(Material $material)
    {
        // check if have access
        $content = file_get_contents($this->getParameter('course_file_directory').'/'.$material->getPath());

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$material->getPath());

        $response->setContent($content);
        return $response;
    }

    /**
     * Deletes a Material entity.
     *
     * @Route("/{id}", name="material_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Material $material)
    {
        if ($this->getCompany() != $material->getCourse()->getCompany()) {
            throw $this->createAccessDeniedException();
        }
        $course = $material->getCourse();
        $form = $this->createDeleteForm($material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $filename = $material->getPath();
            $em->remove($material);
            $em->flush();

            $filesystem = new Filesystem();
            $filesystem->remove($this->getParameter('course_file_directory') . '/' . $filename);
        }

        return $this->redirectToRoute('course_edit', array('id' => $course->getId()));
    }

    /**
     * Creates a form to delete a Material entity.
     *
     * @param Material $material The Material entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Material $material)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('material_delete', array('id' => $material->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Save file
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @return string
     */
    private function saveFile($file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('course_file_directory'),
            $fileName
        );
        return $fileName;
    }

    private function getCompany()
    {
        $user= $this->get('security.context')->getToken()->getUser();
        if (! $this->get('security.context')->isGranted('ROLE_COMPANY') and ! $this->get('security.context')->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('PortalBundle:Company')->findOneByOwner($user);

        if ($this->get('security.context')->isGranted('ROLE_TEACHER')) {
            $teacher = $em->getRepository('PortalBundle:Teacher')->findOneByUser($user);

            $company = $teacher->getCompany();

        }

        return $company;
    }
}
