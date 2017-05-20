<?php

namespace ELearning\CompanyPortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamQuestion
 *
 * @ORM\Table(name="exam_question")
 * @ORM\Entity(repositoryClass="ELearning\CompanyPortalBundle\Repository\ExamQuestionRepository")
 */
class ExamQuestion
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
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="ELearning\CompanyPortalBundle\Entity\Exam", inversedBy="questions")
     * @ORM\JoinColumn(name="exam_id", referencedColumnName="id")
     */
    private $exam;

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
     * Set text
     *
     * @param string $text
     *
     * @return ExamQuestion
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
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
}

