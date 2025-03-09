import Quill from 'quill';
import 'quill/dist/quill.snow.css';
$(document).ready(function () {
    const quill = new Quill('#editor_pengumuman', {
        bounds: '#full-editor',
        modules: {
            toolbar: [
                [{ 'header': '1'}, {'header': '2'}, { 'font': [] }, { 'size': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['bold', 'italic', 'underline'],
                [{ 'align': [] }],
                ['link'],
                ['image'],
                ['blockquote']
            ]
        },
        theme: 'snow'
    });

    quill.on("text-change", function () {
        $("#editor_quil").val(quill.root.innerHTML);
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

    // Custom validator untuk Quill Editor
    $.validator.addMethod("quillRequired", function (value, element) {
        return value !== "<p><br></p>" && value !== "";
    }, "Konten tidak boleh kosong");

    $("#formPengumuman").validate({
        ignore: "",
        rules: {
            judul: {
                required: true
            },
            editor_quil: {
                quillRequired: true
            },
            gambar_header: {
                filesize: 5242880,
                fileextension: ['jpg', 'jpeg', 'png', 'gif']
            }
        },
        messages: {
            judul: {
                required: "Judul wajib diisi"
            },
            editor_quil: {
                required: "Konten tidak boleh kosong"
            },
            gambar_header: {
                filesize: "Ukuran file maksimal 5 MB",
                fileextension: "Hanya file JPG, JPEG, PNG, GIF, dan PDF yang diperbolehkan"
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "editor_quil") {
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
