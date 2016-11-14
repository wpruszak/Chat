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

    public function indexAction(Request $request) {

        // replace this example code with whatever you need
        return $this->render('ChatBundle:Chat:index.html.twig');
    }
}
