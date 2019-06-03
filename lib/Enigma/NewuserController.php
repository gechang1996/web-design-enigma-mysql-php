<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 12:07 AM
 */

namespace Enigma;


class NewuserController
{
    public function __construct(Site $site,array $post,array &$session)
    {
        $root = $site->getRoot();
        if(isset($post['cancel'])){
            $this->redirect = "$root/index.php";
        }
        if(isset($post['ok'])){
            if (strip_tags(trim($post['name']))==''){
                $this->redirect="$root/newuser.php?e";
                $session["ErrorMessage"] = <<<HTML
<p class="msg">You must supply a name.</p>
HTML;
            }
            elseif(strip_tags(trim($post['email']))==''){
                $this->redirect="$root/newuser.php?e";
                $session["ErrorMessage"] = <<<HTML
<p class="msg">You must supply an email address.</p>
HTML;
            }

            else{
                $email = strip_tags($post['email']);
                $name = strip_tags($post['name']);
                $users = new Users($site);
                if($users->exists($email)){
                    $this->redirect="$root/newuser.php?e";
                    $session["ErrorMessage"] = <<<HTML
<p class="msg">Email address already exists.</p>
HTML;
                }
                else {


                    $row = array('id' => 0,
                        'email' => $email,
                        'name' => $name,
                        'password' => null,
                        'date' => date("Y-m-d H:i:s")
                    );
                    $editUser = new User($row);

                    $mailer = new Email();
                    $users->add($editUser, $mailer);

                    $this->redirect = "$root/newuserpending.php";
                }
            }
        }
    }
    public function getRedirect()
    {
        return $this->redirect;
    }	///< Page we will redirect the user to.
    private $redirect;
}