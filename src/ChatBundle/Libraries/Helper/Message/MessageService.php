<?php

namespace ChatBundle\Libraries\Helper\Message;

use ChatBundle\Entity\Message;
use ChatBundle\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * Service responsible for message operations.
 *
 * @author Wojciech Pruszak
 * @package ChatBundle\Libraries\Helper\Message
 */
class MessageService {

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Default constructor.
     *
     * @param EntityManager $em
     */
    public function __construct($em) {
        $this->em = $em;
    }

    /**
     * Creates message and saves it to database.
     * Returns true if succeeded, else returns false.
     *
     * @param User $user
     * @param string $messageContent
     * @return boolean
     */
    public function createAndSaveMessage($user, $messageContent) {

        try {
            $message = new Message();
            $message->setContent($messageContent);
            $message->setDateSent(new \DateTime());
            $message->setUser($user);
            $message->setIsApproved($user->getRole() === User::USER_ROLE_NORMAL ? false : true);

            $this->em->persist($message);
            $this->em->flush();
        } catch(\Exception $e) {
            return false;
        }

        return true;
    }

}
