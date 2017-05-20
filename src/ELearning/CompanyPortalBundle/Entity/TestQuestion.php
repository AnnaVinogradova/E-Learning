<?php

namespace ELearning\CompanyPortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TestQuestion
 *
 * @ORM\Table(name="test_question")
 * @ORM\Entity(repositoryClass="ELearning\CompanyPortalBundle\Repository\TestQuestionRepository")
 */
class TestQuestion
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
     * @ORM\ManyToOne(targetEntity="ELearning\CompanyPortalBundle\Entity\Test", inversedBy="questions")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id")
     */
    private $test;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\QuestionAnswer", mappedBy="question")
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
     * Set text
     *
     * @param string $text
     *
     * @return TestQuestion
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
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param mixed $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }
}

