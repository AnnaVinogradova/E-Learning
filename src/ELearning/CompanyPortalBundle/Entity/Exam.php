<?php

namespace ELearning\CompanyPortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="exam")
 * @ORM\Entity(repositoryClass="ELearning\CompanyPortalBundle\Repository\ExamRepository")
 */
class Exam
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="ELearning\CompanyPortalBundle\Entity\Course", inversedBy="exam")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\ExamQuestion", mappedBy="exam")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\UserExam", mappedBy="exam")
     */
    private $userexams;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Exam
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param mixed $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

}

