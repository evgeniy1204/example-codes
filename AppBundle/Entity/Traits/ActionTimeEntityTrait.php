<?php

namespace BetaOmega\AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ActionTimeEntityTrait {

	/**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime") 
     */
    protected $createdAt;

	/**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true) 
     */
    protected $updatedAt;

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * 
     * @ORM\PrePersist
     *
     * @return Synopses
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * 
     * @ORM\PreUpdate
     *
     * @return Synopses
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}