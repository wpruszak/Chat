<?php

namespace ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $userService = $this->get('user_service');

        // Retrieve user from session.
        $user = $userService->getUser($session);
        $userService->trySettingNick($user, $request->request->get('nick'));

        // If token exists, try promoting user.
        $token = $request->query->get('token');
        if($token !== null) {
            $userService->tryPromoting($user, $token);
        }

        // If nick not selected, now is the time.
        if($user->getNick() === null) {
            return $this->render('ChatBundle:Chat:ask_for_nick.html.twig');
        }

        $messageService = $this->get('message_service');

        return $this->render('ChatBundle:Chat:index.html.twig', array(
            'messages' => $messageService->getMessagesForUser($user)
        ));
    }

    /**
     * Saves message to the database, therefore sends it.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMessageAction(Request $request) {

        // Retrieve user from session.
        $user = $this->get('user_service')->getUser($this->get('session'));

        // Create and save message.
        $isSent = $this->get('message_service')->createAndSaveMessage($user, $request->request->get('message'));

        // If message was not sent, set error flag to 1.
        return new JsonResponse(array('error' => $isSent ? 0 : 1));
    }
}
