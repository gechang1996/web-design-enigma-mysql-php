<?php
/**
 * Controller for the form on the main (index) page.
 * @author Charles B. Owen
 */

namespace Enigma;

/**
 * Controller for the form on the main (index) page.
 */

class IndexController extends Controller {
	/**
	 * IndexController constructor.
	 * @param System $system The System object
	 * @param array $post $_POST
	 */
	public function __construct(System $system, array $post,array &$session,Site $site) {
		parent::__construct($system);
        $users = new Users($site);
        $email = strip_tags($post['name']);
        $password = strip_tags($post['password']);
        $user = $users->login($email, $password);
        $session['batch_dec']="";
        $session['batch_enc']="";
        $session['send_dec']="";
        $session['send_enc']="";
        $session['receive_dec']="";
        $session['receive_dec']="";
        $session["message_number"]="0";
        $_SESSION["code"]="";
        $session["ChosenReceiver"]=array();
        $session[User::SESSION_NAME] = $user;
		// Default will be to return to the home page

		// Clear any error messages
		$system->clearMessages();

        $root = $site->getRoot();
        if($user === null) {
            // Login failed
            $session["ErrorMessage"] = <<<HTML
<p class="msg">Invalid login credentials</p>
HTML;
            $this->setRedirect("../?&e");
        }
        else{

            $name=$user->getName();

            $system->setUser($name);
            $this->setRedirect("../enigma.php");
        }








	}
}