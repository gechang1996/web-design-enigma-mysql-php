<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/15/2018
 * Time: 9:10 PM
 */

namespace Enigma;


class NewuserView extends View
{
    public function __construct(System $system,array &$session,$get) {
        parent::__construct($system, View::NEWUSER);
        if(isset($get['e'])){
            $this->message=$session['ErrorMessage'];
        }
        else{
            $this->message='';
        }
    }
    public function presentHeader() {
        $html = parent::presentHeader();

        return $html;
    }
    public function presentBody()
    {
        $html=<<<HTML
<div class="body">
<form class="dialog" method="post" action="post/newuser.php">
	<p>Creating an account on The Endless Engima will allow you to send and receive messages.</p>
	<div class="controls">
	<p class="name"><label for="name">Name </label><br><input type="text" id="name" name="name"></p>
	<p class="name"><label for="email">Email </label><br><input type="email" id="email" name="email"></p>
	<p><button name="ok" value="ok">Create Account</button></p>
	<p><button name="cancel">Cancel</button></p></div>
	$this->message
	<p>By creating an account on The Endless Enigma, you are grant permission for others users of the system
	to view your name as you have provided it. You are not required to use your real name in The Endless Enigma,
	you may use a pseudonym if you wish. The email address you enter must be valid, but will not be disclosed
	to users of the system.</p>
	<p>Offensive pseudonyms or message content are strictly prohibited.</p>
</form>
</div>
HTML;
        return $html;
    }
    private $message='';
}