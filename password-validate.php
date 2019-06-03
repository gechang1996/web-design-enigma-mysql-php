<?php
require 'lib/enigma.inc.php';
$view = new Enigma\PasswordValidateView($site, $_GET,$_SESSION,$system);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>The Endless Enigma new user Register</title>
    <?php echo $view->head()?>
</head>

<body>
<!--<div class="password">-->


    <?php
    echo $view->present();
    ?>


<!--</div>-->
<!---->
</body>

</html>