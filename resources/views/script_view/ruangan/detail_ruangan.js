import Quill from 'quill';
import 'quill/dist/quill.snow.css';
$(document).ready(function () {
    const quill = new Quill('#editor_template', {
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

    // Custom validator untuk Quill Editor
    $.validator.addMethod("quillRequired", function (value, element) {
        return value !== "<p><br></p>" && value !== "";
    }, "Konten tidak boleh kosong");

    $("#formPengumuman").validate({
        ignore: "",
        rules: {
            nama_jenis: {
                required: true
            },
            editor_quil: {
                quillRequired: true
            }
        },
        messages: {
            nama_jenis: {
                required: "Nama jenis surat wajib diisi"
            },
            editor_quil: {
                required: "Template tidak boleh kosong"
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
