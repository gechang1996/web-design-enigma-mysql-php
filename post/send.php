<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 12:05 AM
 *
 *
 */

//var_dump($_SESSION["ChosenReceiver"]);

//var_dump($_POST);
require '../lib/enigma.inc.php';
$controller = new Enigma\SendController($site, $_POST,$_SESSION,$system);
header("location: " . $controller->getRedirect());