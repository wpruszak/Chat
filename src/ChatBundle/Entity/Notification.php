<?php

namespace ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="ChatBundle\Repository\NotificationRepository")
 */
class Notification
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
     * @ORM\Column(name="isApproved", type="boolean")
     */
    private $isApproved;

    /**
     * @var Message
     *
     * @ORM\OneToOne(targetEntity="Message", inversedBy="notification")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     */
    private $message;

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
     * Set isApproved
     *
     * @param boolean $isApproved
     *
     * @return Notification
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * Get isApproved
     *
     * @return bool
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }

    /**
     * Set message
     *
     * @param \ChatBundle\Entity\Message $message
     *
     * @return Notification
     */
    public function setMessage(\ChatBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \ChatBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
