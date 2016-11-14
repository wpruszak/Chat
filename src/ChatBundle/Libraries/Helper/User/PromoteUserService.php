<?php

namespace ChatBundle\Libraries\Helper\User;

use ChatBundle\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * Class responsible for promoting user to either
 * moderator or expert.
 *
 * @author Wojciech Pruszak
 * @package ChatBundle\Libraries\Helper
 */
class PromoteUserService {

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
