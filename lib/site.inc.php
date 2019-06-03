<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/8/2018
 * Time: 7:16 PM
 */
require __DIR__ . "/../vendor/autoload.php";



$site = new Enigma\Site();
$localize = require 'localize.inc.php';
if(is_callable($localize)) {
    $localize($site);
}

// Start the session system
session_start();
$user = null;
if(isset($_SESSION[Enigma\User::SESSION_NAME])) {
    $user = $_SESSION[Enigma\User::SESSION_NAME];
}

// redirect if user is not logged in
if(!isset($open) && $user === null) {
    $root = $site->getRoot();
    header("location: $root/");
    exit;
}