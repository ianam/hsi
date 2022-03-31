// Captcha check
function recaptchaCallback() {
    $('#recaptcha-check').val(1);
}

$(document).ready(function() {
    // Feature section animation
    $(".feature").each(function() {
        rand = Math.random() * (800);
        $(this).delay(rand).animate({ opacity: 1 }, 500);
    })

    if (location.hash !== null && location.hash !== "") {
        $(location.hash + ".collapse").collapse("show");
    }

    // Form handling
    $('form').on('submit', function(e) {
        let dataString = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'email-contact.php',
            data: dataString,
            success: function() {
                $('#contact-form').html("<div id='message'></div>");
                $('#message')
                    .html("<h2>Message sent!</h2>")
                    .append("<p>Thank you - we will be in touch soon.</p>")
                    .hide()
                    .fadeIn(1500, function() {
                        $('#message')
                    });
            }
        });

        e.preventDefault();
    });
});