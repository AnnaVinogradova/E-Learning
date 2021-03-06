<?php

namespace ELearning\CompanyPortalBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="ELearning\CompanyPortalBundle\Repository\CompanyRepository")
 */
class Company
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/jpeg" },
     *      mimeTypesMessage = "Allowed logo type: jpeg"
     * )
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=500, nullable=true)
     */
    private $site;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\Vacancy", mappedBy="company")
     */
    private $vacancies;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\News", mappedBy="company")
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\Course", mappedBy="company")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="ELearning\CompanyPortalBundle\Entity\Teacher", mappedBy="company")
     */
    private $teachers;

    public function __construct() {
        $this->vacancies = new ArrayCollection();
        $this->news = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

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
     * @return Company
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
     * Set description
     *
     * @param string $description
     *
     * @return Company
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Company
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return Company
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return mixed
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @return mixed
     */
    public function getVacancies()
    {
        return $this->vacancies;
    }

    /**
     * @return mixed
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * @return mixed
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * @param mixed $teachers
     */
    public function setTeachers($teachers)
    {
        $this->teachers = $teachers;
    }
}

