<?php

// Required if your environment does not handle autoloading
require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$sid = get_option('sid_key');
$token = get_option('auth_token');
$client = new Client($sid, $token);
$msgFrom = get_option('twilio_phone');
$msgTo = $phone_number;
$msg1 = "Your Verification Code is ".$smsCode;
//die($phone_number);
// Use the client to do fun stuff like send text messages!
try{
    $message = $client->messages->create(
// the number you'd like to send the message to
        $msgTo,
        array(
            // A Twilio phone number you purchased at twilio.com/console
            'from' => $msgFrom,
            // the body of the text message you'd like to send
            'body' => $msg1
        )
    );
}
catch (Exception $ex){
    echo false;
    die();
}

$sid = $message->sid;
?>



