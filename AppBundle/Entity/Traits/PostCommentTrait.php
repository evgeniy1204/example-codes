<?php

namespace BetaOmega\AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait PostCommentTrait {

	 /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    protected $text;


    /**
     * @ORM\ManyToOne(targetEntity="BetaOmega\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

 /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Question
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Question
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
     * Set user
     *
     * @param \BetaOmega\UserBundle\Entity\User $user
     *
     * @return Question
     */
    public function setUser(\BetaOmega\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BetaOmega\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}