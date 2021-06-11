<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';

if (array_key_exists('to', $_POST)) {
    $err = false;
    $msg = '';
    $email = '';

    // Basic validations: first name, last name
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

    // Email validation
    if (array_key_exists('email', $_POST) && PHPMailer:validateAddress($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $msg .= "Error: invalid email address provided";
        $err = true;
    }

    // Retrieve checkbox list
    $select_types = 'None';
    if(isset($_POST['type']) && is_array($_POST['type']) && count($_POST['type']) > 0){
        $select_types = implode(', ', $_POST['type']);

    $tel = $_POST['tel'];
    $field_exp = $_POST['field'];
    $yrs_exp = $_POST['yrs-exp'];
    $desig = $_POST['desig'];
    $elig = $_POST['elig'];
    $avail = $_POST['avail'];

    if (!err) {
        $mail = new PHPMailer();
        $mail->SetFrom('careers@healthstaffing.ca', 'Health Staffing');
        $mail->addAddress('careers@healthstaffing.ca');
        $mail->addReplyTo($email);
        $mail->Subject = 'Application form submission from Healthstaffing.ca';
        $mail->Body = "Application Form submission from Healthstaffing.ca\n".
                        "First Name: $fname\n".
                        "Last Name: $lname\n".
                        "Phone: $tel\n".
                        "Email: $email\n".
                        "Field of experience: $field_exp\n".
                        "Years of experience: $yrs_exp\n".
                        "Designation: $desig\n".
                        "Eligible to work in Canada: $elig\n".
                        "Availability start date: $avail\n".
                        "Employment type: " . $select_types;
        if (!mail->send()) {
            $msg .= 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $msg .= 'Message sent!';
        }
    }
} ?>