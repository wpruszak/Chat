<?php

namespace ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ChatController
 *
 * @author Wojciech Pruszak
 * @package AppBundle\Controller
 */
class ChatController extends Controller {

    /**
     * Default action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {

        $session = $this->get('session');
        // Retrieve user from session.
        $user = $this->get('retrieve_user_service')->getUser($session);

        // If token exists, try promoting user.
        $token = $request->query->get('token');
        if($token !== null) {
            $this->get('promote_user_service')->tryPromoting($user, $token);
        }

        return $this->render('ChatBundle:Chat:index.html.twig');
    }
}
