<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (array_key_exists('message', $_POST)) {
    $err = false;
    $msg = '';
    $email = '';

    // Server-side validations
    if (array_key_exists('fname', $_POST)) {
        // Limit length & strip HTML tags
        $fname = substr(strip_tags($_POST['fname']), 0, 255);
    } else {
        $fname = '';
    }

    if (array_key_exists('lname', $_POST)) {
        // Limit length & strip HTML tags
        $lname = substr(strip_tags($_POST['lname']), 0, 255);
    } else {
        $lname = '';
    }

    if (array_key_exists('phone', $_POST)) {
        // Limit length & strip HTML tags
        $phone = substr(strip_tags($_POST['phone']), 0, 255);
    } else {
        $phone = '';
    }

    // Validate email
    if (array_key_exists('email', $_POST) && PHPMailer::validateAddress($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $msg .= 'Error: invalid email address provided';
        $err = true;
    }

    if(array_key_exists('message', $_POST)) {
        // Limit length & strip HTML tags
        $message = substr(strip_tags($_POST['message']), 0, 16384);
    } else {
        $message = '';
        $msg = 'No message provided';
        $err = true;
    }

    if (!$err) {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = 'info@healthstaffing.ca';
        $mail->Password = 'hs$2021van';
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        $mail->setFrom('info@healthstaffing.ca', 'Contact Form');
        $mail->addAddress('info@healthstaffing.ca');
        $mail->addReplyTo($email, $fname . $lname);

        $mail->Subject = 'New contact form submission from healthstaffing.ca';

        $mail->Body = "Contact form submission\n\n" . $message . $fname . $lname . $phone . $email;

        if (!$mail->send()) {
            $msg .= 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $msg .= 'Message sent!';
        }
    }
} ?>