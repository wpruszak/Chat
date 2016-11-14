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
            $message->setDateApproved($user->getRole() === User::USER_ROLE_NORMAL ? null : new \DateTime());

            $this->em->persist($message);
            $this->em->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Returns first 100 messages for given user.
     *
     * @param User $user
     * @return Message[]
     */
    public function getMessagesForUser($user) {

        if($user->getRole() === User::USER_ROLE_NORMAL) {
            return $this->em->getRepository('ChatBundle:Message')->createQueryBuilder('m')
                ->where('m.isApproved = :unapproved AND m.user = :user')
                ->orWhere('m.isApproved = :approved')
                ->orderBy('m.dateApproved', 'DESC')
                ->addOrderBy('m.dateSent', 'DESC')
                ->setParameter('user', $user)
                ->setParameter('unapproved', 0)
                ->setParameter('approved', 1)
                ->getQuery()
                ->getResult();
        }

        // If user is not moderator we can't show him unapproved messages.
        $paramArray = $user->getRole() === User::USER_ROLE_EXPERT ? array('isApproved' => true) : array();

        return $this->em->getRepository('ChatBundle:Message')->findBy(
            $paramArray,
            array('dateApproved' => 'DESC', 'dateSent' => 'DESC'),
            100
        );
    }

    /**
     * Approves or deletes message with given messageId.
     *
     * @param $messageId
     * @param $toApprove
     */
    public
    function approveOrDelete($messageId, $toApprove) {

        if (!$toApprove) {
            // Do not fetch message from db, remove it via proxy.
            $message = $this->em->getPartialReference('ChatBundle:Message', array('id' => $messageId));
            $this->em->remove($message);
            $this->em->flush();
            return;
        }

        $message = $this->em->getRepository('ChatBundle:Message')->findOneBy(array('id' => $messageId));
        $message->setIsApproved(true);
        $message->setDateApproved(new \DateTime());
        $this->em->persist($message);
        $this->em->flush();
    }

}
