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

        return $this->render('ChatBundle:Chat:index.html.twig');
    }
}
