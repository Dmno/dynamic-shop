$(document).ready(function () {

    $('.loginModal').on("click", function () {
        $("#indexModal").slideDown('fast');
    });

    // Hide the modal on x click
    $('.closeModal').on("click", function () {
        $("#indexModal").slideUp('fast');
    });

    $('#login-submit').on("click", function () {
        $('#login_email').css("border-color","#ced4da");
        $('.email-error').slideUp('fast');
        $('.credential-error').slideUp('fast');

        $('#login-submit').prop('disabled', true);
        let form = $('#loginForm')[0];

        $.ajax({
            type: "POST",
            url: "/login-action",
            data: new FormData(form),
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                let status = response.status;

                if (status === "Signed in") {
                    window.location.reload();
                } else {
                    if (status === "Invalid email") {
                        $('#login_email').css("border-color","red");
                        $('.email-error').html('Invalid email address').slideDown('fast');
                    }

                    if (status === "No such user") {
                        $('#login_email').css("border-color","red")
                        $('.email-error').html('No such user is registered').slideDown('fast');
                    }

                    if (status === "Invalid credentials") {
                        $('.credential-error').slideDown('fast');
                    }

                    $('#login-submit').prop('disabled', false);
                }
            }
        });
    });
});