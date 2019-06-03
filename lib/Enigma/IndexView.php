<?php
/**
 * View class for the Index (main) page
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * View class for the Index (main) page
 */

class IndexView extends View {

    /**
     * IndexView constructor.
     * @param System $system The System object
     */
	public function __construct(System $system,array &$session,$get) {
		parent::__construct($system, View::INDEX);
		$system->clear();
		if(isset($get['e'])){
            $this->message = $session["ErrorMessage"];
        }
        else{
		    $this->message='';
        }

	}

	/**
	 * Present the page header
	 * @return string HTML
	 */
	public function presentHeader() {
		$html = parent::presentHeader();

		$html .= <<<HTML
<h1 class="center">Welcome to Chang Ge's Endless Enigma!</h1>
HTML;

		return $html;
	}

	/**
	 * Present the page body
	 * @return string HTML
	 */
	public function presentBody() {
		$system = $this->getSystem();

		$html = <<<HTML
<div class="body">
<form class="dialog" method="post" action="post/index-post.php">
	<div class="controls">
	<p class="name"><label for="name">Email </label><br><input type="email" id="name" name="name"></p>
	<p class="password"><label for="name">Password </label><br><input type="password" id="password" name="password"></p>
	<p><button name="ok">Login</button></p>
	<div class="error_message">
	$this->message
	</div>
	<div class="new_user">
<p><a href="newuser.php">New User</a></p>	</div>
</div>
</form>
</div>
HTML;

		return $html;
	}
	private $message='';
}