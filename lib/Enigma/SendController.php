<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 5:38 PM
 */

namespace Enigma;


class SendController extends Controller
{
    public function __construct(Site $site,array $post,array &$session,System $system)
    {
        parent::__construct($system);
        $this->session=$session;
        $root = $site->getRoot();
        $this->site=$site;

    if (isset($post['searcher'])){
        $q=strip_tags(trim($post['search']));
        if(strlen($q)<3){
            $this->redirect="$root/send.php?r";
            $session["ErrorMessage"]=<<<HTML
<p class="message">Search strings must be at least 3 letters long</p>
HTML;
        }
        else{
            $this->redirect="$root/recipients.php?q=$q";
            return;
        }



}
        if(isset($post['remove'])){
            $id=strip_tags($post['remove']);
            $this->redirect="$root/send.php";
            $array1=$session["ChosenReceiver"];
            $array2=array();
            foreach ($array1 as $my_id){
                $array2[$my_id]=$my_id;
            }
            unset($array2[$id]);
            $session["ChosenReceiver"]=array();
            foreach ($array2 as $my_id2){
                $session["ChosenReceiver"][]=$my_id2;
            }

            return;
        }
        if(isset($post['cancel'])) {
            $this->redirect="$root/send.php";
            return;
        }

        if(isset($post['set'])) {
            $this->redirect=$this->redirect="$root/send.php";
            $system->setMessage(View::SEND, '');


            // If we cancel, we ignore the input completely


            // print_r($post);

            $rotors = [];

            for ($r = 1; $r <= 3; $r++) {
                $rotor = strip_tags($post["rotor-$r"]);
                $setting = strip_tags($post["initial-$r"]);
                $setting = strtoupper($setting);

                if (strlen($setting) !== 1 ||
                    strcmp($setting, 'A') < 0 || strcmp($setting, 'Z') > 0) {
                    $system->setMessage(View::SEND, "Invalid setting for rotor $r");
                    return;
                }

                $rotors[] = ['rotor' => $rotor, 'setting' => $setting];
            }

            //
            // Ensure no duplicate rotor
            //
            if ($rotors[0]['rotor'] == $rotors[1]['rotor'] ||
                $rotors[0]['rotor'] == $rotors[2]['rotor'] ||
                $rotors[1]['rotor'] == $rotors[2]['rotor']) {
                $system->setMessage(View::SEND, 'You are not allowed to use the same rotor more than once.');
                return;
            }

            $system->setRotors($rotors);

        }
        if(isset($post['encode'])){
            $code=strip_tags(strtoupper($post['code']));
            if((ctype_alpha($code)) and (strlen($code)==3)){
                $_SESSION["code"]=$code;
                $this->redirect="$root/send.php";

                $from = strip_tags($post['from']);
                //
                // First the machine rotors are set
                // That process is what you used on Project 1
                //

                $enigma=$this->getSystem()->getEnigma();
                // Then, assuming the three letters are in $code, we do:
                $c1 = $enigma->pressed(substr($code, 0, 1));
                $c2 = $enigma->pressed(substr($code, 1, 1));
                $c3 = $enigma->pressed(substr($code, 2, 1));

                $enigma->setRotorSetting(1, $c1);
                $enigma->setRotorSetting(2, $c2);
                $enigma->setRotorSetting(3, $c3);

                // Then we encode (or decode) the message
                $this->encode($from);

                $system->reset();
                $session['send_dec']=strtoupper($system->getEncoded());
                $session['send_enc']=strtoupper($system->getDecoded());
            }
            else{
                $session["ErrorMessage"]=<<<HTML
<p class="message">Code must be three alphabetic characters</p>
HTML;
               $this->redirect="$root/send.php?e";

            }

        }
        if(isset($post['send'])){
        $this->redirect="$root/send.php";
        $allid=$session['ChosenReceiver'];
        $messages=new Messages($site);
        $user=$session[User::SESSION_NAME];
        if(strip_tags($post['enc']) !="" and strip_tags($post['code'])!="") {


            foreach ($allid as $id1) {

                $content = strip_tags($post['enc']);
                $sender = $user->getId();
                $receiver = $id1;
                $code = strip_tags($post['code']);
                $row = array('id' => 0,
                    'content' => $content,
                    'sender' => $sender,
                    'receiver' => $receiver,
                    'date' => date("Y-m-d H:i:s"),
                    'code' => $code,

                );
                $message = new Message($row);
                $messages->sendMessage($message);
            }
        }
            $session['code']="";
            $session['ChosenReceiver']=array();
            $system->setDecoded("");
            $system->setEncoded("");
            $session['send_enc']="";
            $session['send_dec']="";

        }




    }


    private function encode($text) {
        $system = $this->getSystem();
        // $system->reset();

        $system->setDecoded($text);

        $encoded = '';

        for($i=0; $i<strlen($text); $i++) {
            $ch = strtoupper(substr($text, $i, 1));
            if(strcmp($ch, 'A') >= 0 && strcmp($ch , 'Z') <= 0) {
                $encoded .= $this->send($ch);
            } else {
                switch($ch) {
                    case '.':
                        $encoded .= $this->send('X');
                        break;

                    case '0':
                        $encoded .= $this->send('NULL');
                        break;

                    case '1':
                        $encoded .= $this->send('EINZ');
                        break;

                    case '2':
                        $encoded .= $this->send('ZWO');
                        break;

                    case '3':
                        $encoded .= $this->send('DREI');
                        break;

                    case '4':
                        $encoded .= $this->send('VIER');
                        break;

                    case '5':
                        $encoded .= $this->send('FUNF');
                        break;

                    case '6':
                        $encoded .= $this->send('SEQS');
                        break;

                    case '7':
                        $encoded .= $this->send('SIEBEN');
                        break;

                    case '8':
                        $encoded .= $this->send('ACHT');
                        break;

                    case '9':
                        $encoded .= $this->send('NEUN');
                        break;
                }
            }

        }

        //
        // Split into substrings of 5 characters
        //
        $encoded5 = '';
        for($i=0; $i<strlen($encoded); $i+=5) {
            if(strlen($encoded5) > 0) {
                $encoded5 .= ' ';
            }

            $encoded5 .= substr($encoded, $i, 5);
        }
        $system->setEncoded($encoded5);
    }

    /**
     * Decode a message
     * @param $text string Message to decode
     */


    /**
     * Send a known valid string to the Enigma
     * @param $str string String to send
     * @return string Transcoded version of the string.
     */
    private function send($str) {
        $result = '';
        for($i=0; $i<strlen($str); $i++) {
            $ch = substr($str, $i, 1);
            $result .= $this->getSystem()->getEnigma()->pressed($ch);
        }

        return $result;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }	///< Page we will redirect the user to.
    private $redirect;
    private $session;
    private $site;
}