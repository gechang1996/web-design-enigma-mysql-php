<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 1:29 PM
 */

namespace Enigma;


class ReceiveView extends View
{
    /**
     * BatchView constructor.
     * @param System $system The System object
     */
    public function __construct(System $system,Site $site,$post,array &$session,$get) {
        parent::__construct($system, View::RECEIVE);
        $user=$session[User::SESSION_NAME];
        $this->users=new Users($site);
        $this->user_id=$user->getId();
        $this->messages=new Messages($site);

        $this->get=$get;
        $this->session=$session;
        if(isset($get['e'])){
        $this->error_mes=$session["ErrorMessage"];}
        else{
            $this->error_mes="";
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

        $dec = $system->getDecoded();
        $enc = $system->getEncoded();

        $html=<<<HTML
        <div class="body">
<form method="post" action="post/receive.php">
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
</div> 
HTML;

        if($this->session["message_number"]!="0"){
            $dec = $this->session['receive_dec'];


            $my_message=$this->messages->get($this->session["message_number"]);
            $my_code=$my_message->getCode();
            $content=$my_message->getContent();
            $html.=<<<HTML


<div class="encoder dialog">
<p class="code">Code: $my_code</p>
<p><textarea disabled name="from">$dec</textarea> <textarea disabled name="to">$content</textarea></p>
<input type="hidden" name="content" value="$content">
<input type="hidden" name="code" value="$my_code">
HTML;
            $html.=<<<HTML

</div>
HTML;
        }





        $html.=<<<HTML
        
<div class="dialog"><table class="receive">
<tr><th>Select</th><th>Time</th><th>Sender</th></tr>
HTML;

        $users=$this->users;
        $receive_messages=$this->messages->receiveMessage($this->user_id);
        $mes_number=$this->session["message_number"];

        foreach ($receive_messages as $message){
            $mes_id=$message->getId();
            $date=$message->getDate();
            $date=strtotime($date);
            $date=date("M d, Y h:ia",$date);
            $sender=$message->getSender();
            $user=$users->get($sender);
            $sender_name=$user->getName();

            if($mes_id==$mes_number){
                $html.=<<<HTML
<tr><td><input value="$mes_id" type="radio" name="message" checked></td><td>$date</td><td>$sender_name</td></tr>
HTML;
            }
            else{


            $html.=<<<HTML
<tr><td><input value="$mes_id" type="radio" name="message"></td><td>$date</td><td>$sender_name</td></tr>
HTML;
            }
        }




$html.=<<<HTML

</table><p><input type="submit" value="View" name="view"></p>$this->error_mes</div></form>
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
    private $messages;
    private $user_id;
    private $users;
    private $get;
    private $session;
    private $error_mes;
}