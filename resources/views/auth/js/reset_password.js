$(document).ready(function () {
    $.validator.addMethod("hasCapital", function(value, element) {
        return /[A-Z]/.test(value);
    }, "Password harus mengandung minimal satu huruf kapital.");

    // Custom method untuk mengecek apakah password mengandung minimal satu karakter spesial
    $.validator.addMethod("hasSpecialChar", function(value, element) {
        return /[!@#$%^&*(),.?":{}|<>]/.test(value);
    }, "Password harus mengandung minimal satu karakter spesial.");

    $("#formAuthentication").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8, // minimal 8 karakter
                hasCapital: true, // minimal satu huruf kapital
                hasSpecialChar: true // minimal satu karakter spesial
            },
            password_confirmation: {
                required: true,
                equalTo: "#password" // harus sama dengan password
            }
        },
        messages: {
            email: {
                required: "Email wajib diisi",
                email: "Format email tidak valid"
            },
            password: {
                required: "Password wajib diisi",
                minlength: "Minimal 8 karakter", // minimal 8 karakter
                hasCapital: "Minimal satu huruf kapital", // minimal satu huruf kapital
                hasSpecialChar: "Minimal satu karakter spesial" // minimal satu karakter spesial
            },
            password_confirmation: {
                required: "Password konfirmasi wajib diisi",
                equalTo: "Password konfirmasi harus sama" // harus sama dengan password
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "password") {
                error.appendTo("#error-password");
            } else if(element.attr("name") === "password_confirmation") {
                error.appendTo("#error-password_confirmation");
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
