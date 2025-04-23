$(document).ready(function () {
    tinymce.init({
        selector: '#editor_pengumuman',
        plugins: 'link image code table lists wordcount',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist',
        menubar: 'file edit insert format table',
        skin: false, // karena kita import manual
        content_css: false, // karena kita import manual
        license_key: 'gpl',
        content_style: `
            body {
              padding-left: 20px;
              padding-right: 20px;
              padding-bottom: 20px;
              padding-top: 20px;
            }
          `,
        setup: function (editor) {
            // Tambahkan tombol kustom ke toolbar
            editor.on('init', function () {
                $('#editor-loading').hide();
                $('.tox-promotion').hide();
            });
        },
        onchange_callback: function(editor) {
            tinyMCE.triggerSave();
            $("#" + editor.id).valid();
        }
    });

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

    $("#formPengumuman").validate({
        ignore: "",
        rules: {
            judul: {
                required: true
            },
            editor_pengumuman: {
                required: true
            },
            gambar_header: {
                required: true,
                filesize: 5242880,
                fileextension: ['jpg', 'jpeg', 'png', 'gif']
            }
        },
        messages: {
            judul: {
                required: "Judul wajib diisi"
            },
            editor_pengumuman: {
                required: "Konten tidak boleh kosong"
            },
            gambar_header: {
                required: "Gambar Header wajib diisi",
                filesize: "Ukuran file maksimal 5 MB",
                fileextension: "Hanya file JPG, JPEG, PNG, GIF yang diperbolehkan"
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "editor_pengumuman") {
                error.appendTo("#error-quil");
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
