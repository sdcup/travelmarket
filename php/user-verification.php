<?php

require_once "php/users.php";

// check if this is a email verification avatar of this page
if(isset($_GET['email']) && !empty($_GET['email']) &&
   isset($_GET['token']) && !empty($_GET['token'])) {
    // Verify data
    if(User::activateUser($_GET['email'], $_GET['token'])) {
        // we are good
        $outputStr = "Thank you for joining travelmarket<br>your account is now active";
    } else {
        // give user the bad news
        $outputStr =  "Sorry, there is no user with that name in our system.";
    }
    header("LOCATION: http://www.travelmarket.com?showpage=login");
    echo $outputStr;
}

?>
