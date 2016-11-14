<?php

namespace ChatBundle\Libraries\Util;

/**
 * Class GenerateUUIDService
 *
 * @author Wojciech Pruszak
 * @package ChatBundle\Libraries\Util
 */
class GenerateUUIDService {

    /**
     * Generates UUID string.
     *
     * @author Andrew Moore
     * @see http://www.php.net/manual/en/function.uniqid.php#94959
     * @return string
     */
    public function generateUUIDv4() {

        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}