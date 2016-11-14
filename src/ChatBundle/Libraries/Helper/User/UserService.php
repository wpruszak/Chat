<?php

namespace ChatBundle\Libraries\Helper\User;

use ChatBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Service responsible for user operations.
 *
 * @author Wojciech Pruszak
 * @package ChatBundle\Libraries\Helper
 */
class UserService {

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
     * Tries setting given users nickname to the
     * one given. Returns true if succeeded, else returns
     * false.
     *
     * @param User $user
     * @param string $nick
     * @return boolean
     */
    public function trySettingNick($user, $nick) {

        if($nick !== null && !empty($nick)) {
            $user->setNick($nick);
            $this->em->persist($user);
            $this->em->flush($user);
            return true;
        }

        return false;
    }

    /**
     * Retrieves user from given session.
     * If there is no user, creates it.
     *
     * @param SessionInterface $session
     * @return User
     */
    public function getUser($session) {

        $userId = $session->get('userId');

        // If session does not contain user id, create user.
        if($userId === null) {
            $user = new User();
            $user->setSessionId($session->getId());
            $user->setRole(User::USER_ROLE_NORMAL);
            $this->em->persist($user);
            $this->em->flush();
            $session->set('userId', $user->getId());
            // Else just fetch user from database.
        } else {
            $user = $this->em->getRepository('ChatBundle:User')->findOneBy(array('id' => $userId));
        }

        return $user;
    }

    /**
     * Tries promoting user to either moderator or expert.
     * Returns true if promotion was successful or false if
     * was not.
     *
     * @param User $user
     * @param string $token
     * @return boolean
     */
    public function tryPromoting($user, $token) {

        $promoted = false;

        // If given token is valid, promote user to its
        // corresponding type.
        if(trim($token) === User::USER_TOKEN_EXPERT) {
            $user->setRole(User::USER_ROLE_EXPERT);
            $promoted = true;
        } else if (trim($token) === User::USER_TOKEN_MODERATOR) {
            $user->setRole(User::USER_ROLE_MODERATOR);
            $promoted = true;
        }

        // If user was promoted, save the user.
        if($promoted) {
            $this->em->persist($user);
            $this->em->flush();
        }

        return $promoted;
    }
}
