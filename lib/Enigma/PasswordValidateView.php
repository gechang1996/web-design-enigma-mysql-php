<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/15/2018
 * Time: 7:06 PM
 */

namespace Enigma;


class PasswordValidateView extends View
{
    public function __construct(Site $site,$get,array &$session,$system)
    {
        parent::__construct($system, View::VALIDATE);


        $this->site=$site;
        $this->validator = strip_tags($get['v']);
//        var_dump($session["ErrorMessage"]);
        if (isset($get['e'])) {

            $this->message = $session["ErrorMessage"];
        }
    }
    public function presentHeader() {
        $html = parent::presentHeader();

        return $html;
    }
    public function presentBody() {
        $html = <<<HTML
        <div class="body">
<form class="dialog" action="post/password-validate.php" method="post">

<input type="hidden" name="validator" value="$this->validator">
<div class="controls">
		<p>
			<label for="email">Email</label><br>
			<input type="email" id="email" name="email" placeholder="Email">
		</p>
		<p>
			<label for="password">Password:</label><br>
			<input type="password" id="password" name="password" placeholder="password">
		</p>
		<p>
			<label for="password2">Password (again):</label><br>
			<input type="password" id="password2" name="password2" placeholder="password">
		</p>
		<p><button name="ok">Create Account</button></p>
	    <p><button name="cancel">Cancel</button></p>
		<div class="error_message">
		$this->message
		</div>
		</div>
		
</form>
</div>
HTML;

        return $html;
    }
    private $site;
    private $validator;
    private $message='';

}