<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 2:20 PM
 */

namespace Enigma;


class RecipientsView extends View
{
    /**
     * BatchView constructor.
     * @param System $system The System object
     */
    public function __construct(System $system,Site $site,$get) {
        parent::__construct($system, View::RECIPIENTS);

        $this->site=$site;
        $this->get=$get;
        $this->name=$get['q'];
    }

    /**
     * Preset the page header
     * @return string HTML
     */
    public function presentHeader() {
        $html = parent::presentHeader();

        return $html;
    }

    /**
     * Present the page body
     * @return string HTML
     */
    public function presentBody() {

        $users=new Users($this->site);
        $all=$users->getUsers();
        $user_input=$this->get['q'];
        $result=false;
        $available_users=array();
        foreach ($all as $my_user){
            $my_name=$my_user->getName();
            if (strpos($my_name,$user_input)!==false){
                $available_users[]=$my_user;
                $result=true;

            }
        }



        if($result==true) {

            $html=<<<HTML
        <div class="body">
<form method="post" action="post/recipients.php">
<input type="hidden" name="search" value="$this->name">
<div class="dialog receipients"><p>Select a user to add to the list of recipients.</p>
<table class="recipt">
HTML;
            foreach ($available_users as $user) {
                $id = $user->getId();
                $name = $user->getName();
                $html .= <<<HTML
<tr><td><input type="radio" name="recipient" value="$id"</td><td>$name</td></tr>
HTML;

            }

            $html.=<<<HTML


</table>

<p><input type="submit" name="add" value="Add"> <input type="submit" name="cancel" value="Cancel"></p></div>
</div>
</form>
</div>
HTML;
        }

        else{
            $html=<<<HTML
        <div class="body">
<form method="post" action="post/recipients.php">
<div class="dialog receipients"><p>Query returned no results!</p>
<p><input type="submit" name="cancel" value="Ok"></p></div>
</form>
</div>
HTML;
        }

        return $html;
    }
    /**
     * Create the form controls for a single rotor
     * @param $rotor Rotor number 1-3
     * @return string HTML
     */
    private function rotor($rotor) {
        $system = $this->getSystem();
        $enigma = $system->getEnigma();

        $setting = $enigma->getRotorSetting($rotor);
        $wheel = $enigma->getRotor($rotor);

        $html = <<<HTML
<p><label for="rotor-$rotor">Rotor $rotor:</label>
<select id="rotor-$rotor" name="rotor-$rotor">
HTML;

        $rotors = ['', 'I', 'II', 'III', 'IV', 'V'];
        for($i=1; $i<=5; $i++) {
            $id = $rotors[$i];
            $selected = $wheel == $i ? " selected" : "";
            $html .= <<<HTML
<option value="$i"$selected>$id</option>
HTML;

        }
        $html .= <<<HTML
</select>&nbsp;&nbsp;
<label for="initial-$rotor">Setting:</label>
<input class="initial" id="initial-$rotor" name="initial-$rotor" type="text" value="$setting">
</p>
HTML;

        return $html;
    }
    private $site;
    private $get;
    private $name;
}