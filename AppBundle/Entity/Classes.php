<?php

namespace BetaOmega\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Classes
 *
 * @ORM\Table(name="classes")
 * @ORM\Entity(repositoryClass="BetaOmega\AppBundle\Repository\ClassesRepository")
 */
class Classes
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
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="classes")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id" )
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="\BetaOmega\SynopsesBundle\Entity\Synopses", mappedBy="classes")
     */
    private $synopses;

    /**
     * @ORM\OneToMany(targetEntity="\BetaOmega\UserBundle\Entity\User", mappedBy="classes")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->synopses = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getClassesFromCourse($course)
    {
        if ($this->getCourse() == $course) {
            die;
            return $this->name;
        }
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return Classes
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
     * Set course
     *
     * @param \BetaOmega\AppBundle\Entity\Course $course
     *
     * @return Classes
     */
    public function setCourse(\BetaOmega\AppBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \BetaOmega\AppBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Add synopsis
     *
     * @param \BetaOmega\SynopsesBundle\Entity\Synopses $synopsis
     *
     * @return Classes
     */
    public function addSynopsis(\BetaOmega\SynopsesBundle\Entity\Synopses $synopsis)
    {
        $this->synopses[] = $synopsis;

        return $this;
    }

    /**
     * Remove synopsis
     *
     * @param \BetaOmega\SynopsesBundle\Entity\Synopses $synopsis
     */
    public function removeSynopsis(\BetaOmega\SynopsesBundle\Entity\Synopses $synopsis)
    {
        $this->synopses->removeElement($synopsis);
    }

    /**
     * Get synopses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSynopses()
    {
        return $this->synopses;
    }

    /**
     * Add user
     *
     * @param \BetaOmega\UserBundle\Entity\User $user
     *
     * @return Classes
     */
    public function addUser(\BetaOmega\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \BetaOmega\UserBundle\Entity\User $user
     */
    public function removeUser(\BetaOmega\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }
}
