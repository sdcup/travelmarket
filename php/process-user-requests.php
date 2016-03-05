<?php

    /* process user  requests related to
    ** registration
    ** login
    ** changepassword
    ** forgotpassword
    ** updateprofile
    */

require_once "users.php";
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


$ud = json_decode($_POST['requestDetails']);

function createVerificationToken($tokSize) {
    $charStr = '0123456789'.
                'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.
                'abcdefghijklmnopqrstuvwxyz';

    $strSize = strlen($charStr)-1;
    $t = '';

    // create token using random characters from the string
    for ($idx = 0; $idx < $tokSize; $idx++) {
        $t .= $charStr[mt_rand(0, $strSize)];
    }

    return $t;
}

switch($_POST['op']) {
    case 'registeruser' :
        try {
            $vToken = createVerificationToken(24);
            $u = User::createUser($ud->emailId, $ud->pwd, $ud->emailId,
                            $ud->fName, $ud->lName, "", "", "", "", "", "", $vToken);

            // send a verification email to user
            $notificationUrl = 'http://'.TMConfig::$hName."?email=".$ud->emailId."&token=".$vToken;
            $msg = "\n\nWelocome to travel market\n\n".
                "Please verify your email by clicking on the following URL\n\n".
                $notificationUrl."\n\n".
                "travelmarket\nnever pay outrageous prices for air tickets again";
            sendMail($ud->emailId, "Verification email from travelmarket", $msg);

            $ret['status'] = 'success';
            $ret['msg']  = "User Created";
        } catch (Exception $e) {
            $ret['status'] = 'error';
            $ret['msg']  = $e->getMessage();
        }
        break;
    case 'changepassword' :
    case 'login' :
    case 'updateprofile' :
    default: {
        $ret['status'] = 'error';
        $ret['responseText']  =  "you want me to do $op....I don't know how to do that";
    }

}

echo json_encode($ret);

?>
