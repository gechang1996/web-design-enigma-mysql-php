<?php
require_once "lib/enigma.inc.php";
$view = new Enigma\SendView($system,$_SESSION,$_GET,$site);


if($view->getRedirect() !== null) {
    header("location: " . $view->getRedirect());
    exit;
}

//var_dump($_SESSION["ChosenReceiver"]);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>The Endless Enigma</title>
    <?php echo $view->head(); ?>
</head>

<body>
<?php
echo $view->present();
?>
</body>
</html>
