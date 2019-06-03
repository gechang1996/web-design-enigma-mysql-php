<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/12/2018
 * Time: 7:40 PM
 */

require '../lib/enigma.inc.php';
//var_dump($_GET);
//var_dump($_POST);
$controller = new Enigma\PasswordValidateController($site, $_POST,$_SESSION);
header("location: " . $controller->getRedirect());