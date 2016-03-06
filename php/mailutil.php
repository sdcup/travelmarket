<?php

// Pear Mail Library
require_once "Mail.php";

function sendMail($to, $subj, $msg) {
    $from = '<tm.verify@yahoo.com>'; //change this to your email address

    $headers = array(
        'From' => $from,
        'To' => $to,
        'Subject' => $subj
    );

    $smtp = Mail::factory('smtp', array(
            'host' => TMConfig::$mSMTPHost,
            'port' => TMConfig::$mPort,
            'auth' => true,
            'username' => TMConfig::$mUserName, //your gmail account
            'password' => TMConfig::$mPassword,
        ));

    // Send the mail
    $mail = $smtp->send($to, $headers, $msg);
}

?>
