<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 1:25 AM
 */

namespace Enigma;


class PasswordValidateController
{
    /**
     * PasswordValidateController constructor.
     * @param Site $site The Site object
     * @param array $post $_POST
     */
    public function __construct(Site $site, array $post,array &$session) {
        $root = $site->getRoot();
        $this->redirect = "$root/";
        if(isset($post['ok'])) {
            //
            // 1. Ensure the validator is correct! Use it to get the user ID.
            //
            $validators = new Validators($site);
            $validator = strip_tags($post['validator']);
            $userid = $validators->get($validator);

            if ($userid === null) {
                $this->redirect = "$root/password-validate.php?v=$validator&e";
                $session["ErrorMessage"] =<<<HTML
<p class="msg">Invalid or unavailable validator</p>
HTML;
                return;
            }
            //
            // 2. Ensure the email matches the user.
            //
            $users = new Users($site);
            $editUser = $users->get($userid);
            if ($editUser === null) {
                // User does not exist!
                $session["ErrorMessage"] =<<<HTML
<p class="msg">Email address is not for a valid user</p>
HTML;
                $this->redirect = "$root/password-validate.php?v=$validator&e";
                return;
            }
            $email = trim(strip_tags($post['email']));
            if ($email !== $editUser->getEmail()) {
                // Email entered is invalid
                $session["ErrorMessage"] =<<<HTML
<p class="msg">Email address does not match validator</p>
HTML;
                $this->redirect = "$root/password-validate.php?v=$validator&e";
                return;
            }

            //
            // 3. Ensure the passwords match each other
            //
            $password1 = trim(strip_tags($post['password']));
            $password2 = trim(strip_tags($post['password2']));
            if ($password1 !== $password2) {
                // Passwords do not match
                $session["ErrorMessage"] =<<<HTML
<p class="msg">Passwords did not match</p>
HTML;
                $this->redirect = "$root/password-validate.php?v=$validator&e";
                return;
            }

            if (strlen($password1) < 8) {
                // Password too short
                $session["ErrorMessage"] =<<<HTML
<p class="msg">Password too short</p>
HTML;
                $this->redirect = "$root/password-validate.php?v=$validator&e";
                return;
            }
            //
            // 4. Create a salted password and save it for the user.
            //
            $users->setPassword($userid, $password1);
            //
            // 5. Destroy the validator record so it can't be used again!
            //
            $validators->remove($userid);
        }


    }
    public function getRedirect()
    {
        return $this->redirect;
    }	///< Page we will redirect the user to.
    ///
    private $redirect;
}