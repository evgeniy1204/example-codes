<?php

namespace BetaOmega\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="BetaOmega\AppBundle\Repository\CourseRepository")
 */
class Course
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
     * @ORM\OneToMany(targetEntity="Classes", mappedBy="course")
     *
     * @var ArrayCollection $classes
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity="YearOfStudy", mappedBy="course")
     *
     * @var ArrayCollection $year
     */
    private $year;

    /**
     * @ORM\OneToMany(targetEntity="BetaOmega\SynopsesBundle\Entity\Synopses", mappedBy="course")
     */
    private $synopses;

    /**
     * @ORM\OneToMany(targetEntity="BetaOmega\UserBundle\Entity\User", mappedBy="course")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->synopses = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->year = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * @return Course
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
     * Add class
     *
     * @param \BetaOmega\AppBundle\Entity\Classes $class
     *
     * @return Course
     */
    public function addClass(\BetaOmega\AppBundle\Entity\Classes $class)
    {
        $this->classes[] = $class;

        return $this;
    }

    /**
     * Remove class
     *
     * @param \BetaOmega\AppBundle\Entity\Classes $class
     */
    public function removeClass(\BetaOmega\AppBundle\Entity\Classes $class)
    {
        $this->classes->removeElement($class);
    }

    /**
     * Get classes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Add year
     *
     * @param \BetaOmega\AppBundle\Entity\YearOfStudy $year
     *
     * @return Course
     */
    public function addYear(\BetaOmega\AppBundle\Entity\YearOfStudy $year)
    {
        $this->year[] = $year;

        return $this;
    }

    /**
     * Remove year
     *
     * @param \BetaOmega\AppBundle\Entity\YearOfStudy $year
     */
    public function removeYear(\BetaOmega\AppBundle\Entity\YearOfStudy $year)
    {
        $this->year->removeElement($year);
    }

    /**
     * Get year
     *
     * @return \Doctrine\Common\Collections\Collection
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
     * @return Course
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
     * @return Course
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
