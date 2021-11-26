// $(document).ready(function() {
//     $('form').on('submit', function(e) {
//         let dataString = $(this).serialize();

//         $.ajax({
//             type: 'POST',
//             url: 'email-contact.php',
//             data: dataString,
//             success: function() {

//                 $('#contact-form').html("<div id='message'></div>");
//                 $('#message')
//                     .html("<h2>Contact form submitted!</h2>")
//                     .append("<p>We will be in touch soon.</p>")
//                     .hide()
//                     .fadeIn(1500, function() {
//                         $('#message')
//                     });
//             }
//         });

//         e.preventDefault();
//     });
// });