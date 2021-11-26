<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (array_key_exists('fname', $_POST)) {
    $err = false;
    $msg = '';
    $email = '';

    // Retrieve personal info variables
    $phone = $_POST['phone'];
    $postcode = $_POST['postcode'];
    $country = $_POST['country'];

    // Validations for text entries
    if (array_key_exists('fname', $_POST)) {
        $fname = substr(strip_tags($_POST['fname']), 0, 255);
    } else {
        $fname = '';
    }

    if (array_key_exists('lname', $_POST)) {
        $lname = substr(strip_tags($_POST['lname']), 0, 255);
    } else {
        $lname = '';
    }

    if (array_key_exists('address', $_POST)) {
        $address = substr(strip_tags($_POST['address']), 0, 255);
    } else {
        $address = '';
    }

    if (array_key_exists('city', $_POST)) {
        $city = substr(strip_tags($_POST['city']), 0, 255);
    } else {
        $city = '';
    }

    if (array_key_exists('province', $_POST)) {
        $province = substr(strip_tags($_POST['province']), 0, 255);
    } else {
        $province = '';
    }

    // Validation for email
    if (array_key_exists('email', $_POST) && PHPMailer::validateAddress($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $msg .= 'Error: invalid email address provided';
        $err = true;
    }

    // Retrieve professional info variables
    $fieldExp = $_POST['fieldExp'];
    $elig = $_POST['elig'];

    if (array_key_exists('yearsExp', $_POST)) {
        $yearsExp = substr(strip_tags($_POST['yearsExp']), 0, 255);
    } else {
        $yearsExp = '';
    }

    if (array_key_exists('desig', $_POST)) {
        $desig = substr(strip_tags($_POST['desig']), 0, 255);
    } else {
        $desig = '';
    }

    if (array_key_exists('avail', $_POST)) {
        $avail = substr(strip_tags($_POST['avail']), 0, 255);
    } else {
        $avail = '';
    }
    
    // Retrieve checkbox list
    $select_types = 'None';
    if (isset($_POST['type']) && is_array($_POST['type']) && count($_POST['type']) > 0){
        $select_types = implode(', ', $_POST['type']);
    }

    // Get attachment
    if (array_key_exists('resume', $_FILES)) {
        // Get the extension
        $ext = PHPMailer::mb_pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
        // Filer file format
        $allowTypes = array("doc", "docx", "pdf", "txt");
        if (in_array($ext, $allowTypes)) {
            // Move uploaded file
            $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['resume']['name'])) . '.' . $ext;
        } else {
            $msg .= 'Error: invalid file type';
            $err = true;
        } 
    }

    // Email construction (no errors & file uploaded)
    if (!$err && move_uploaded_file($_FILES['resume']['tmp_name'], $uploadfile)) {
        
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = 'iana.markevitch@gmail.com';
        $mail->Password = 'i73823064';
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        $mail->setFrom('iana.markevitch@gmail.com', 'Application Form');
        $mail->addAddress('iana.markevitch@gmail.com');
        $mail->addReplyTo($email);

        $mail->Subject = 'New Application Form Submission';

        $mail->isHTML(true);
        $mail->Body = "
        <html>
            <head>
                <style>
                    table { border: 1px solid #ddd; border-radius: 10px; padding: 5px; }
                    body { font-family: sans-serif; }
                    th, td { padding: 10px; }
                    td:first-child { background-color: #ffecdb; font-weight: bold; border-radius: 5px; }
                </style>
            </head>
            <body>
                <h3>Personal Information</h3>
                <table>
                    <tr>
                        <td>First name:</td>
                        <td>$fname</td>
                    </tr>
                    <tr>
                        <td>Last name:</td>
                        <td>$lname</td>
                    </tr>
                    <tr>
                        <td>Phone number:</td>
                        <td>$phone</td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td>$address</td>
                    </tr>
                    <tr>
                        <td>City:</td>
                        <td>$city</td>
                    </tr>
                    <tr>
                        <td>Province/State:</td>
                        <td>$province</td>
                    </tr>
                    <tr>
                        <td>Postal/Zip code:</td>
                        <td>$postcode</td>
                    </tr>
                    <tr>
                        <td>Country:</td>
                        <td>$country</td>
                    </tr>
                </table>
                <h3>Professional Information</h3>
                <table>
                    <tr>
                        <td>Field of experience:</td>
                        <td>$fieldExp</td>
                    </tr>
                    <tr>
                        <td>Years of experience:</td>
                        <td>$yearsExp</td>
                    </tr>
                    <tr>
                        <td>Designation:</td>
                        <td>$desig</td>
                    </tr>
                    <tr>
                        <td>Availability start:</td>
                        <td>$avail</td>
                    </tr>
                    <tr>
                        <td>Eligible to work in Canada:</td>
                        <td>$elig</td>
                    </tr>
                    <tr>
                        <td>Employment type:</td>
                        <td>$select_types</td>
                    </tr>
                </table>
            </body>
        </html>
        ";

        $mail->addAttachment($uploadfile, 'Uploaded resume');

        if($mail->send()){
            echo 'Message sent';
        } else {
            echo 'Message could not be sent';
            echo 'Mailer Error: ' . $mail->ErrorInfo . $msg;
        }
    } else {
        $msg .= 'Failed to move file to ' . $uploadfile;
    }   
}

?>