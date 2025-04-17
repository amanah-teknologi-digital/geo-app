import Quill from 'quill';
import 'quill/dist/quill.snow.css';
$(document).ready(function () {
    const quill = new Quill('#editor_surat', {
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

    if (!isEdit){
        quill.disable();
    }

    quill.on("text-change", function () {
        $("#editor_quil").val(quill.root.innerHTML);
    });

    // Custom validator untuk Quill Editor
    $.validator.addMethod("quillRequired", function (value, element) {
        return value !== "<p><br></p>" && value !== "";
    }, "Konten tidak boleh kosong");

    $("#formPengajuan").validate({
        ignore: "",
        rules: {
            id_pengajuan:{
                required: true
            },
            jenis_surat: {
                required: true
            },
            editor_quil: {
                quillRequired: true
            },
            keterangan: {
                required: true
            }
        },
        messages: {
            id_pengajuan: {
                required: "Id Pengajuan wajib diisi"
            },
            jenis_surat: {
                required: "Jenis surat wajib diisi"
            },
            editor_quil: {
                required: "Konten tidak boleh kosong"
            },
            keterangan: {
                required: "Keterangan wajib diisi"
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

    $('#jenis_surat').on('change', function() {
        let id_jenissurat = $(this).val();

        if (id_jenissurat) {
            $.ajax({
                url: routeGetJenisSurat,
                type: 'GET',
                data: { id_jenissurat: id_jenissurat },
                dataType: 'json',
                success: function(response) {
                    quill.root.innerHTML = response.default_form;
                },
                error: function(xhr, status, error) {
                    quill.root.innerHTML = '';
                }
            });
        } else {
            quill.root.innerHTML = '';
        }
    });
})
