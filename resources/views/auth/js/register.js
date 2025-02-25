$(document).ready(function () {
    // Custom method untuk validasi ukuran file (misal max 2MB)
    $.validator.addMethod("filesize", function(value, element, param) {
        // Cek jika file dipilih
        if(element.files.length === 0) {
            return true;
        }
        // Ukuran file dalam bytes
        return element.files[0].size <= param;
    }, "Ukuran file terlalu besar.");

    // Custom method untuk validasi tipe file (misal hanya jpg dan png)
    $.validator.addMethod("fileextension", function(value, element, param) {
        if(element.files.length === 0){
            return true;
        }
        // Dapatkan nama file dan ekstrak ekstensi
        var fileName = element.files[0].name;
        var extension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
        return $.inArray(extension, param) !== -1;
    }, "Tipe file tidak diperbolehkan.");

    $.validator.addMethod("hasCapital", function(value, element) {
        return /[A-Z]/.test(value);
    }, "Password harus mengandung minimal satu huruf kapital.");

    // Custom method untuk mengecek apakah password mengandung minimal satu karakter spesial
    $.validator.addMethod("hasSpecialChar", function(value, element) {
        return /[!@#$%^&*(),.?":{}|<>]/.test(value);
    }, "Password harus mengandung minimal satu karakter spesial.");

    $("#formAuthentication").validate({
        rules: {
            nama_lengkap: {
                required: true
            },
            no_kartuid: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            no_telepon: {
                required: true,
                number: true,
                maxlength: 13
            },
            file_kartuid: {
                required: true,
                filesize: 5242880,
                fileextension: ['jpg', 'jpeg', 'png', 'gif']
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
            },
            terms: {
                required: true
            }
        },
        messages: {
            nama_lengkap: {
                required: "Nama Lengkap wajib diisi"
            },
            email: {
                required: "Email wajib diisi",
                email: "Format email tidak valid"
            },
            file_kartuid: {
                required: "Harap pilih file untuk diupload",
                filesize: "Ukuran file maksimal 5 MB",
                fileextension: "Hanya file JPG, JPEG, dan PNG yang diperbolehkan"
            },
            no_kartuid:{
                required: "Kartu ID wajib diisi"
            },
            no_telepon: {
                required: "No HP wajib diisi",
                number: "Hanya angka yang diperbolehkan",
                max: "Panjang maksimal karakter 13"
            },
            terms: {
                required: "Centang terms & kondisi"
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
            } else if(element.attr("name") === "terms") {
                error.appendTo("#error-terms");
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
