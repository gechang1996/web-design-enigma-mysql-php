<?php
/**
 * Created by PhpStorm.
 * User: Chang Ge
 * Date: 6/16/2018
 * Time: 12:14 AM
 */

namespace Enigma;


class NewuserpendingView extends View
{
    public function __construct(System $system) {
        parent::__construct($system, View::NEWUSERPENDING);
    }
    public function presentHeader() {
        $html = parent::presentHeader();

        return $html;
    }
    public function presentBody()
    {
        $html=<<<HTML
        <p></p>
<form class="newgame" method="get" action="post/index-post.php">
    <div class="controls dialog">
        <p>An email message has been sent to your address. When it arrives, select the
        validate link in the email to validate your account.</p>
        <p><button>Home</button></p>
    </div>
</form>
<p></p>

HTML;
        return $html;
    }
}