<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/17/2018
 * Time: 2:33 PM
 */

namespace Enigma;


class ReceiveController extends Controller
{
    public function __construct(Site $site,array $post,array &$session,System $system)
    {
        parent::__construct($system);
        $root = $site->getRoot();
        if(isset($post['view'])){
            if(!isset($post['message'])){
                $this->redirect="$root/receive.php?e";
                $session["ErrorMessage"]=<<<HTML
<p class="message">Select a message to decode.</p>
HTML;

                return;
            }
            else{
                $session["message_number"]=strip_tags($post['message']);
                $messages=new Messages($site);
                $message=$messages->get(strip_tags($post['message']));
                $code1=$message->getCode();
                $content1=$message->getContent();
                $enigma=$this->getSystem()->getEnigma();
                // Then, assuming the three letters are in $code, we do:
                $c1 = $enigma->pressed(substr($code1, 0, 1));
                $c2 = $enigma->pressed(substr($code1, 1, 1));
                $c3 = $enigma->pressed(substr($code1, 2, 1));

                $enigma->setRotorSetting(1, $c1);
                $enigma->setRotorSetting(2, $c2);
                $enigma->setRotorSetting(3, $c3);

                // Then we encode (or decode) the message
                $this->decode($content1);
                $session["receive_dec"]=$system->getDecoded();

                $system->reset();

                $this->redirect="$root/receive.php";

                return;

            }
        }


        if(isset($post['set'])) {
            $this->redirect=$this->redirect="$root/receive.php";
            $system->setMessage(View::RECEIVE, '');


            // If we cancel, we ignore the input completely


            // print_r($post);

            $rotors = [];

            for ($r = 1; $r <= 3; $r++) {
                $rotor = strip_tags($post["rotor-$r"]);
                $setting = strip_tags($post["initial-$r"]);
                $setting = strtoupper($setting);

                if (strlen($setting) !== 1 ||
                    strcmp($setting, 'A') < 0 || strcmp($setting, 'Z') > 0) {
                    $system->setMessage(View::RECEIVE, "Invalid setting for rotor $r");
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
                $system->setMessage(View::RECEIVE, 'You are not allowed to use the same rotor more than once.');
                return;
            }

            $system->setRotors($rotors);
            if(isset($post['content'])){
                $code=strip_tags($post['code']);
                $from = strip_tags($post['content']);
                $enigma=$system->getEnigma();
                // Then, assuming the three letters are in $code, we do:
                $c1 = $enigma->pressed(substr($code, 0, 1));
                $c2 = $enigma->pressed(substr($code, 1, 1));
                $c3 = $enigma->pressed(substr($code, 2, 1));

                $enigma->setRotorSetting(1, $c1);
                $enigma->setRotorSetting(2, $c2);
                $enigma->setRotorSetting(3, $c3);

                // Then we encode (or decode) the message
                $this->decode($from);
                $session["receive_dec"]=$system->getDecoded();
                $system->reset();

            }


        }
        if(isset($post['cancel'])){
            $this->redirect="$root/receive.php";
        }





    }
    /**
     * Decode a message
     * @param $text string Message to decode
     */
    private function decode($text) {
        $system = $this->getSystem();
        //   $system->reset();

        $system->setEncoded($text);

        $decoded = '';

        for($i=0; $i<strlen($text); $i++) {
            $ch = strtoupper(substr($text, $i, 1));
            if(strcmp($ch, 'A') >= 0 && strcmp($ch , 'Z') <= 0) {
                $decoded .= $this->send($ch);
            }
        }

        //
        // Split into substrings of 5 characters
        //
        $encoded5 = '';
        for($i=0; $i<strlen($decoded); $i+=5) {
            if(strlen($encoded5) > 0) {
                $encoded5 .= ' ';
            }

            $encoded5 .= substr($decoded, $i, 5);
        }
        $system->setDecoded($encoded5);
    }

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
}