<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 4:39 PM
 */

namespace Enigma;


class RecipientsController
{
    public function __construct(Site $site,array $post,array &$session)
    {
        $root = $site->getRoot();

        if(isset($post['cancel'])){
            $this->redirect="$root/send.php";
        }
        if(isset($post['add'])){
            $this->redirect="$root/send.php";
            $id=strip_tags($post['recipient']);
            $session["ChosenReceiver"][]=$id;
            $session["ChosenReceiver"]=array_unique($session["ChosenReceiver"]);
        }
    }
    public function getRedirect()
    {
        return $this->redirect;
    }	///< Page we will redirect the user to.
    private $redirect;
}