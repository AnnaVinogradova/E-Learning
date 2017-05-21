<?php

namespace ELearning\CompanyPortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamAnswer
 *
 * @ORM\Table(name="exam_answer")
 * @ORM\Entity(repositoryClass="ELearning\CompanyPortalBundle\Repository\ExamAnswerRepository")
 */
class ExamAnswer
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
     * @var bool
     *
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", nullable=true)
     */
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity="ELearning\CompanyPortalBundle\Entity\ExamQuestion", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="ELearning\CompanyPortalBundle\Entity\UserExam", inversedBy="answers")
     * @ORM\JoinColumn(name="user_exam_id", referencedColumnName="id")
     */
    private $userExam;


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
     * Set finished
     *
     * @param boolean $finished
     *
     * @return ExamAnswer
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
     * Set answer
     *
     * @param string $answer
     *
     * @return ExamAnswer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getUserExam()
    {
        return $this->userExam;
    }

    /**
     * @param mixed $userExam
     */
    public function setUserExam($userExam)
    {
        $this->userExam = $userExam;
    }
}

