<?php

namespace ChatBundle\Libraries\Helper\Message;

use ChatBundle\Entity\Message;
use ChatBundle\Entity\Notification;
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
                ->where('m.isApproved = :unapproved')
                ->andWhere('m.user = :user')
                ->andWhere('m.isDeleted is null')
                ->orWhere('m.isApproved = :approved')
                ->andWhere('m.isDeleted is null')
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
     * Approves or hides message with given messageId.
     *
     * @param $messageId
     * @param $toApprove
     */
    public function approveOrDelete($messageId, $toApprove) {

        $message = $this->em->getRepository('ChatBundle:Message')->findOneBy(array('id' => $messageId));
        $notification = new Notification();
        $notification->setMessage($message);
        $notification->setIsApproved($toApprove);

        if (!$toApprove) {
            $message->setIsDeleted(true);
        } else {
            $message->setIsApproved(true);
            $message->setDateApproved(new \DateTime());
        }

        $this->em->persist($notification);
        $this->em->persist($message);
        $this->em->flush();
    }

    /**
     * Returns user notifications in array of strings.
     *
     * @param User $user
     * @return Notification[]
     */
    public function getNotificationsFor($user) {

        $notificationRepo = $this->em->getRepository('ChatBundle:Notification');

        $notifications = $notificationRepo->createQueryBuilder('n')
            ->leftJoin('n.message', 'm')
            ->leftJoin('m.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();

        $result = array();

        /** @var Notification $notification */
        foreach($notifications as $notification) {
            $result[] = array(
                'message' => $notification->getMessage()->getContent(),
                'success' => $notification->getIsApproved()
            );

            // Remove soon to be shown notifications.
            $this->em->remove($notification);
        }
        $this->em->flush();

        return $result;
    }

}
