<?php

namespace ELearning\CompanyPortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flow
 *
 * @ORM\Table(name="flow")
 * @ORM\Entity(repositoryClass="ELearning\CompanyPortalBundle\Repository\FlowRepository")
 */
class Flow
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="ELearning\CompanyPortalBundle\Entity\Course", inversedBy="flows")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;


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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
}

