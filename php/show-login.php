<?php

if(isset($_GET['showpage']) && !empty($_GET['showpage']))  {
    if(strcmp($_GET['showpage'], "login")==0) {
        require_once "html/login.html";
    }
}

?>
