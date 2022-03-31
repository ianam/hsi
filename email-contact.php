<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'recaptcha/src/autoload.php';

$DatabaseAccess = parse_ini_file('../access.ini');

if (array_key_exists('message', $_POST)) {
    $err = false;
    $msg = '';
    $email = '';
    $captcha = '';

    // Check captcha response
    if (isset($_POST["g-recaptcha-response"]))
        $captcha = $_POST["g-recaptcha-response"];

    if ($captcha == '')
        $err = true; 
    $secret = '6LfpqOweAAAAAA7LYPfEjtR40n5pb-EMzVOHIBD7';
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

    if ($response["success"] = false) {
        $err = true;
    }

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
        $mail->Host = $DatabaseAccess['host'];
        $mail->Port = $DatabaseAccess['port'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = $DatabaseAccess['username'];
        $mail->Password = $DatabaseAccess['password'];
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        $mail->setFrom('info@healthstaffing.ca', 'Contact Form');
        $mail->addAddress('info@healthstaffing.ca');
        $mail->addReplyTo($email, $fname . " " . $lname);

        $mail->Subject = 'New contact form submission from healthstaffing.ca';

        $mail->isHTML(true);
        $mail->Body = "
        <html>
            <head>
                <style>
                    div { margin: auto; max-width: 600px; margin-top: 50px; }
                    section { background-color: #fff; padding-bottom: 50px; }
                    h3 { background-color: #78b5c1; margin: 0; padding: 40px 20px; color: #fff; font-size: 30px; font-weight: 200; border: 1px solid #dcdcdc; border-radius: 5px; }
                    p { margin: 0; padding: 20px 20px;}
                    table { border: 1px solid #ddd; border-radius: 5px; padding: 5px; margin: 0 20px; }
                    body { font-family: sans-serif; background-color: #f5f5f5; }
                    th, td { padding: 10px 50px 10px 10px; }
                    td:first-child { background-color: #78b5c1; color: #fff; border-radius: 5px; }
                </style>
            </head>
            <body>
                <div>
                    <h3>Contact Form</h3>
                    <section>
                        <p>A contact form submission has been made from  healthstaffing.ca.</p>
                        <table>
                            <tr>
                                <td>First name</td>
                                <td>$fname</td>
                            </tr>
                            <tr>
                                <td>Last name</td>
                                <td>$lname</td>
                            </tr>
                            <tr>
                                <td>Phone number</td>
                                <td>$phone</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>$email</td>
                            </tr>
                            <tr>
                                <td>Message</td>
                                <td>$message</td>
                            </tr>
                        </table>
                    </section>
                </div>
            </body>
        </html>
        ";
        
        if (!$mail->send()) {
            $msg .= 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $msg .= 'Message sent!';
        }
    }
} ?>