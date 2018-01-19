<?php

namespace BetaOmega\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YearOfStudy
 *
 * @ORM\Table(name="year_of_study")
 * @ORM\Entity(repositoryClass="BetaOmega\AppBundle\Repository\YearOfStudyRepository")
 */
class YearOfStudy
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
     * @ORM\Column(name="year", type="string", length=255)
     */
    private $year;

    /**
     * @ORM\OneToMany(targetEntity="BetaOmega\SynopsesBundle\Entity\Synopses", mappedBy="year")
     */
    private $synopses;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="year")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id" )
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="BetaOmega\UserBundle\Entity\User", mappedBy="year")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->synopses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * toString
     */
    public function __toString()
    {
        return $this->year;
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
     * Set year
     *
     * @param string $year
     *
     * @return YearOfStudy
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Add synopsis
     *
     * @param \BetaOmega\SynopsesBundle\Entity\Synopses $synopsis
     *
     * @return YearOfStudy
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
     * Set course
     *
     * @param \BetaOmega\AppBundle\Entity\Course $course
     *
     * @return YearOfStudy
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
     * Add user
     *
     * @param \BetaOmega\UserBundle\Entity\User $user
     *
     * @return YearOfStudy
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
