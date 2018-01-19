<?php

namespace BetaOmega\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BetaOmega\AppBundle\Entity\Traits\ActionTimeEntityTrait;

/**
 * Complaints
 *
 * @ORM\Table(name="complaints")
 * @ORM\Entity(repositoryClass="BetaOmega\AppBundle\Repository\ComplaintsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Complaints
{
    use ActionTimeEntityTrait;

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
     * @ORM\Column(name="text", type="string", length=255, nullable=true)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="complaint", type="string", length=255)
     */
    private $complaint;

    /**
     * @ORM\ManyToOne(targetEntity="BetaOmega\SynopsesBundle\Entity\Question", inversedBy="complaints")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=true)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="BetaOmega\UserBundle\Entity\User", inversedBy="complaints")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="BetaOmega\ForumBundle\Entity\Topic", inversedBy="complaints")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="BetaOmega\SynopsesBundle\Entity\Review", inversedBy="complaints")
     * @ORM\JoinColumn(name="review_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $review;

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
     * Set text
     *
     * @param string $text
     *
     * @return Complaints
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
     * Set complaint
     *
     * @param string $complaint
     *
     * @return Complaints
     */
    public function setComplaint($complaint)
    {
        $this->complaint = $complaint;

        return $this;
    }

    /**
     * Get complaint
     *
     * @return string
     */
    public function getComplaint()
    {
        return $this->complaint;
    }

    /**
     * Set question
     *
     * @param \BetaOmega\SynopsesBundle\Entity\Question $question
     *
     * @return Complaints
     */
    public function setQuestion(\BetaOmega\SynopsesBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \BetaOmega\SynopsesBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set user
     *
     * @param \BetaOmega\UserBundle\Entity\User $user
     *
     * @return Complaints
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

    /**
     * Set topic
     *
     * @param \BetaOmega\ForumBundle\Entity\Topic $topic
     *
     * @return Complaints
     */
    public function setTopic(\BetaOmega\ForumBundle\Entity\Topic $topic = null)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \BetaOmega\ForumBundle\Entity\Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set review
     *
     * @param \BetaOmega\SynopsesBundle\Entity\Review $review
     *
     * @return Complaints
     */
    public function setReview(\BetaOmega\SynopsesBundle\Entity\Review $review = null)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return \BetaOmega\SynopsesBundle\Entity\Review
     */
    public function getReview()
    {
        return $this->review;
    }
}
