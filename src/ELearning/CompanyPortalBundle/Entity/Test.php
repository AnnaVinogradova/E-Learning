<?php

namespace ELearning\CompanyPortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity(repositoryClass="ELearning\CompanyPortalBundle\Repository\TestRepository")
 */
class Test
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
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="ELearning\CompanyPortalBundle\Entity\Course", inversedBy="tests")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\TestQuestion", mappedBy="test")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\UserTest", mappedBy="test")
     */
    private $usertests;

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
     * @return Test
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
     * @param mixed $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @return mixed
     */
    public function getUsertests()
    {
        return $this->usertests;
    }

    /**
     * @param mixed $usertests
     */
    public function setUsertests($usertests)
    {
        $this->usertests = $usertests;
    }
}

