<?php

namespace ChatBundle\Libraries\Helper\User;

use ChatBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class responsible for retrieving user from session.
 *
 * @author Wojciech Pruszak
 * @package ChatBundle\Libraries\Helper
 */
class RetrieveUserService {

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

}
