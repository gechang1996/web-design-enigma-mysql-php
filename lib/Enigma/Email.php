<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/15/2018
 * Time: 7:03 PM
 */

namespace Enigma;


class Email
{
    public function mail($to, $subject, $message, $headers) {
        mail($to, $subject, $message, $headers);
    }
}