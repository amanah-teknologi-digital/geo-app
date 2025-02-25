$(document).ready(function () {
    $("#formAuthentication").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "Email wajib diisi",
                email: "Format email tidak valid"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
})
