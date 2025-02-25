$(document).ready(function () {
    $("#formAuthentication").validate({
        rules: {
            password: {
                required: true
            }
        },
        messages: {
            password: {
                required: "Password wajib diisi"
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "password") {
                error.appendTo("#error-password");
            } else {
                // Default: tampilkan setelah elemen
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
})
