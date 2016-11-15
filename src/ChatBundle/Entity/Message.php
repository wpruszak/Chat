<?php

namespace ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="ChatBundle\Repository\MessageRepository")
 */
class Message {

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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="isApproved", type="boolean")
     */
    private $isApproved;

    /**
     * @var bool
     *
     * @ORM\Column(name="isDeleted", type="boolean", nullable=true)
     */
    private $isDeleted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateSent", type="datetime")
     */
    private $dateSent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateApproved", type="datetime", nullable=true)
     */
    private $dateApproved;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {

        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content) {

        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {

        return $this->content;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     *
     * @return Message
     */
    public function setIsApproved($isApproved) {

        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * Get isApproved
     *
     * @return bool
     */
    public function getIsApproved() {

        return $this->isApproved;
    }

    /**
     * Set dateSent
     *
     * @param \DateTime $dateSent
     *
     * @return Message
     */
    public function setDateSent($dateSent) {

        $this->dateSent = $dateSent;

        return $this;
    }

    /**
     * Get dateSent
     *
     * @return \DateTime
     */
    public function getDateSent() {

        return $this->dateSent;
    }

    /**
     * Set user
     *
     * @param \ChatBundle\Entity\User $user
     *
     * @return Message
     */
    public function setUser(\ChatBundle\Entity\User $user = null) {

        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ChatBundle\Entity\User
     */
    public function getUser() {

        return $this->user;
    }

    /**
     * Set dateApproved
     *
     * @param \DateTime $dateApproved
     *
     * @return Message
     */
    public function setDateApproved($dateApproved)
    {
        $this->dateApproved = $dateApproved;

        return $this;
    }

    /**
     * Get dateApproved
     *
     * @return \DateTime
     */
    public function getDateApproved()
    {
        return $this->dateApproved;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Message
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }
}
