// Pear Mail Library
require_once "Mail.php";

function sendMail($to, $subj, $msg) {
    $from = '<sdhingra.client@gmail.com>'; //change this to your email address
    $body = "Hello world! this is the content of the email"; //content of mail

    $headers = array(
        'From' => $from,
        'To' => $to,
        'Subject' => $subj
    );

    $smtp = Mail::factory('smtp', array(
            'host' => 'ssl://smtp.gmail.com',
            'port' => '465',
            'auth' => true,
            'username' => 'sdhingra.client@gmail.com', //your gmail account
            'password' => 'drDhingra1' // your password
        ));

    // Send the mail
    $mail = $smtp->send($to, $headers, $body);
}
