<?php

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

if(!isset($_POST['submit']))
{
    echo "The form needs to be submitted";
}
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $tel = $_POST['tel'];
    $usr_email = $_POST['email'];
    $field_exp = $_POST['field'];
    $yrs_exp = $_POST['yrs-exp'];
    $desig = $_POST['desig'];
    $elig = $_POST['elig'];
    $avail = $_POST['avail'];

    // Validate against email injection attempts
    if(IsInjected($usr_email))
    {
        echo "Invalid email value";
        exit;
    }

    // Retrieve checkbox list
    $select_types = 'None';
    if(isset($_POST['type']) && is_array($_POST['type']) && count($_POST['type']) > 0){
        $select_types = implode(', ', $_POST['type']);
    }

    $email_from = 'careers@healthstaffing.ca';
    $email_subject = "New application form submission from Healthstaffing.ca";
    $email_body = "Application Form submission from Healthstaffing.ca\n".
                    "First Name: $fname\n".
                    "Last Name: $lname\n".
                    "Phone: $tel\n".
                    "Email: $usr_email\n".
                    "Field of experience: $field_exp\n".
                    "Years of experience: $yrs_exp\n".
                    "Designation: $desig\n".
                    "Eligible to work in Canada: $elig\n".
                    "Availability start date: $avail\n".
                    "Employment type: " . $select_types;

    $to = 'careers@healthstaffing.ca';
    $headers = "From: $email_from \r\n";
    $headers .= "Reply-To: $usr_email \r\n";

    mail($to,$email_subject,$email_body,$headers);

    // Validation function
    function IsInjected($str)
    {
        $injections = array('(\n+)',
                    '(\r+)',
                    '(\t+)',
                    '(%0A+)',
                    '(%0D+)',
                    '(%08+)',
                    '(%09+)'
        );
        $inject = join('|', $injections);
        $inject = "/$inject/i";
        if(preg_match($inject,$str))
            {
                return true;
            }
            else
            {
                return false;
            }
    }
?>