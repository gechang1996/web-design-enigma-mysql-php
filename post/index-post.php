<?php
//var_dump($_POST);
require_once "../lib/enigma.inc.php";
$controller = new \Enigma\IndexController($system, $_POST,$_SESSION,$site);
echo $controller->showRedirect();
header("location: " . $controller->getRedirect());