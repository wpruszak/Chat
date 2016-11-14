<?php

namespace ChatBundle\Controller;

use ChatBundle\Entity\User;
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
            'messages' => $messageService->getMessagesForUser($user),
            'isModerator' => $user->getRole() === User::USER_ROLE_MODERATOR ? true : false
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

    /**
     * Returns rendered messages html.
     *
     * @return JsonResponse
     */
    public function retrieveMessagesAction() {

        // Retrieve user from session.
        $user = $this->get('user_service')->getUser($this->get('session'));

        return new JsonResponse(array(
            'html' => $this->renderView('@Chat/Chat/partial/_messages.html.twig', array(
                'messages' => $this->get('message_service')->getMessagesForUser($user)
            ))
        ));
    }

    /**
     * Deletes or approves message.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function decideAction(Request $request) {

        $messageId = $request->request->get('messageId');
        $toApprove = $request->request->get('toApprove') === '1' ? true : false;

        $this->get('message_service')->approveOrDelete($messageId, $toApprove);

        return new JsonResponse(array());
    }
}
