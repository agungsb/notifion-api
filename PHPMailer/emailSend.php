<?php
require_once 'PHPMailer-master/PHPMailerAutoload.php';
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "akbarkusumanegaralth@gmail.com";
$mail->Password = "summeravalanche";
$mail->SetFrom("akbarkusumanegaralth@gmail.com");
$mail->Subject = "Aktivasi User ". $username;
$mail->Body = "Terima kasih telah membuat akun pada laman Penmaba UNJ 2015. Silahkan klik di link ini untuk aktivasi akun anda. <a href='" . $link . "'>KLIK</a>";
$mail->AddAddress($email);
 if(!$mail->Send())
    {
    echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
    echo "Message has been sent";
    }