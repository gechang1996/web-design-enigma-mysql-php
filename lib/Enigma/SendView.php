<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 2:25 AM
 */

namespace Enigma;


class SendView extends View
{
    /**
     * BatchView constructor.
     * @param System $system The System object
     */
    public function __construct(System $system,array &$session,$get,Site $site) {
        parent::__construct($system, View::SEND);

        $this->get=$get;
        $this->message='';
        $this->session=$session;
        if(isset($get['e'])){
            $this->message=$session["ErrorMessage"];
        }
        if(isset($get['r'])){
            $this->message1=$session["ErrorMessage"];
        }
        $this->code=$session["code"];
        $my_ary=$session["ChosenReceiver"];
        $users= new Users($site);
        foreach ($my_ary as $id){
            $this->chosen[]=$users->get($id);
        }





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

        $system = $this->getSystem();
        $enigma = $system->getEnigma();
        $rotor1 = $enigma->getRotorSetting(1);
        $rotor2 = $enigma->getRotorSetting(2);
        $rotor3 = $enigma->getRotorSetting(3);

        $dec = $this->session['send_enc'];
        $enc = $this->session['send_dec'];

        $html=<<<HTML
        <div class="body">
<form method="post" action="post/send.php"><div class="dialog receipients">
<input type="hidden" name="enc" value="$enc">
<p><label for="search">Find Recipients: </label><input type="search" name="search" id="search" placeholder="Search...">
<input type="submit" value="Search" name="searcher"></p>
$this->message1


HTML;
        if(empty($this->chosen)) {

            $html .= <<<HTML
<p>Use search to find recipients for a message to send.</p>


HTML;
        }
        else{
            $html.=<<<HTML
<table class="send_mes">

HTML;


            foreach ($this->chosen as $user){
                $id=$user->getId();
                $name=$user->getName();
                $html.=<<<HTML
<tr><td><button name="remove" value="$id">Remove</button></td><td>&nbsp;&nbsp;&nbsp;$name</td></tr>
HTML;

            }

            $html.=<<<HTML

</table>
HTML;

        }


        $html.=<<<HTML

</div>
<div class="dialog">
HTML;





        $html .= $this->rotor(1);
        $html .= $this->rotor(2);
        $html .= $this->rotor(3);

        $html .= <<<HTML
<p><input type="submit" name="set" value="Set"> <input type="submit" name="cancel" value="Cancel"></p>
HTML;

        $html .= $this->presentMessage();
        $html .= <<<HTML

HTML;



        $html.=<<<HTML

</div> 
<div class="encoder dialog">
<p class="code"><label for="code">Code: </label><input class="my_code" type="text" name="code" id="code" value="$this->code"></p>
<p><textarea name="from">$dec</textarea>&nbsp;<textarea name="to" disabled>$enc</textarea></p>
$this->message
<p><input type="submit" name="encode" value="Encode ->"> <input type="submit" name="send" value="Send"></p>
</div>
</form>
</div>
HTML;

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
    private $get;
    private $message='';
    private $message1='';
    private $code;
    private $chosen=array();
    private $session;
}