<?php

namespace ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ChatBundle\Repository\UserRepository")
 */
class User {

    // Token to gain expert rights.
    const EXPERT_TOKEN = 'V1vxkg2iYkQE3llSlzr6jMz6ZL4uY68uJS2HuLfv';

    // Token to gain moderator rights.
    const MODERATOR_TOKEN = 'ITCRKqqFOQALqZZaUrKaH7cOzhV47TwEV6Svu1i4';

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
     * @ORM\Column(name="nick", type="string", length=255)
     */
    private $nick;

    /**
     * @var int
     *
     * @ORM\Column(name="role", type="integer")
     */
    private $role;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     */
    private $messages;

    /**
     * @var string
     *
     * @ORM\Column(name="sessionId", type="string", length=255, unique=true)
     */
    private $sessionId;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {

        return $this->id;
    }

    /**
     * Set nick
     *
     * @param string $nick
     *
     * @return User
     */
    public function setNick($nick) {

        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string
     */
    public function getNick() {

        return $this->nick;
    }

    /**
     * Set role
     *
     * @param integer $role
     *
     * @return User
     */
    public function setRole($role) {

        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return int
     */
    public function getRole() {

        return $this->role;
    }

    /**
     * Set sessionId
     *
     * @param string $sessionId
     *
     * @return User
     */
    public function setSessionId($sessionId) {

        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string
     */
    public function getSessionId() {

        return $this->sessionId;
    }

    /**
     * Constructor
     */
    public function __construct() {

        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add message
     *
     * @param \ChatBundle\Entity\Message $message
     *
     * @return User
     */
    public function addMessage(\ChatBundle\Entity\Message $message) {

        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \ChatBundle\Entity\Message $message
     */
    public function removeMessage(\ChatBundle\Entity\Message $message) {

        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages() {

        return $this->messages;
    }
}
