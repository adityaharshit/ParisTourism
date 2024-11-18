<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug  = 0;  
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = $smtpUsername;
    $mail->Password   = $smtpPassword;
    
    $mail->IsHTML(true);
    //To address
    $mail->AddAddress($addAddress, "");
    //From address
    $mail->SetFrom($addAddress, $name);
    $mail->AddReplyTo($email, $name);
    $mail->Subject = "New Message from IEEE VTS";
    $content = "Message: $message";
    
    $mail->MsgHTML($content); 
    if($mail->Send()) {
        echo '<script>alert("Response received")</script>';
    } else {
        echo '<script>alert("Encountered an issue while sending the email")</script>';
    }
    echo "<script>setTimeout(\"location.href = 'index.html';\",1500);</script>";
}
?>
