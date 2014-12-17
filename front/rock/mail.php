<?php
$email = ' ';
$name = ' ';
$com = ' ';
if (isset($_POST['email']) && $_POST['email'] != '') {
    $email = $_POST['email'];
}
if (isset($_POST['name']) && $_POST['name'] != '') {
    $name = $_POST['name'];
}
if (isset($_POST['coment']) && $_POST['coment'] != '') {
    $coment = $_POST['coment'];
}
$to = 'arshad.faiyaz@cyberlinks.co.in';
$subject = 'Multitv Front.';
// To send HTML mail, the Content-type header must be set
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .= 'To: Anand Cyberlinks <anand.vyas@cyberlinks.in>' . "\r\n";
$headers .= 'From: MultitvWeb.in <admin@multitv.in>' . "\r\n";
//$headers .= 'Cc: manoj.kakkar@cyberlinks.in' . "\r\n";
### for nest latter

$message = sprintf("Contact us Form,<br> Email:- %s <br> Name:- %s<br>Comment:- %s", $email, $name, $coment);
if (mail($to, $subject, $message, $headers)) {
    echo 'Thank you, We will contact you soon';
} else {
    echo 'Sorry! Something is wrong please try again';
}
?>
