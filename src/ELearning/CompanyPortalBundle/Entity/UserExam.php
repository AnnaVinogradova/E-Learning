<?php

namespace ELearning\CompanyPortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserExam
 *
 * @ORM\Table(name="user_exam")
 * @ORM\Entity(repositoryClass="ELearning\CompanyPortalBundle\Repository\UserExamRepository")
 */
class UserExam
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
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="checked", type="boolean")
     */
    private $checked;

    /**
     * @var bool
     *
     * @ORM\Column(name="sertificate", type="boolean")
     */
    private $sertificate;

    /**
     * @var bool
     *
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="tests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="ELearning\CompanyPortalBundle\Entity\Exam", inversedBy="userexams")
     * @ORM\JoinColumn(name="exam_id", referencedColumnName="id")
     */
    private $exam;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\ExamAnswer", mappedBy="userExam")
     */
    private $answers;

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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return UserExam
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return UserExam
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     *
     * @return UserExam
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return bool
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set sertificate
     *
     * @param boolean $sertificate
     *
     * @return UserExam
     */
    public function setSertificate($sertificate)
    {
        $this->sertificate = $sertificate;

        return $this;
    }

    /**
     * Get sertificate
     *
     * @return bool
     */
    public function getSertificate()
    {
        return $this->sertificate;
    }

    /**
     * Set finished
     *
     * @param boolean $finished
     *
     * @return UserExam
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return bool
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * @param mixed $exam
     */
    public function setExam($exam)
    {
        $this->exam = $exam;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}

