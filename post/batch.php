<?php
require_once "../lib/enigma.inc.php";
$controller = new \Enigma\BatchController($system, $_POST,$_SESSION);
//echo $controller->showRedirect();
header("location: " . $controller->getRedirect());