$(document).ready(function () {
    tinymce.init({
        selector: '#editor',
        plugins: 'link image code table lists wordcount',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
        menubar: true,
        skin: false, // karena kita import manual
        content_css: false, // karena kita import manual
        license_key: 'gpl',
        images_upload_handler: function (blobInfo, success, failure) {
            // base64 (contoh simple)
            success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
        },
        setup: function (editor) {
            editor.on('init', function () {
                // Sembunyikan spinner setelah editor siap
                const loadingEl = document.getElementById('editor-loading');
                if (loadingEl) {
                    loadingEl.style.display = 'none';
                }else {

                }
            });
        }
    });
    // const quill = new Quill('#editor_template', {
    //     bounds: '#full-editor',
    //     modules: {
    //         toolbar: [
    //             [{ 'header': '1'}, {'header': '2'}, { 'font': [] }, { 'size': [] }],
    //             [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    //             ['bold', 'italic', 'underline'],
    //             [{ 'align': [] }],
    //             ['link'],
    //             ['image'],
    //             ['blockquote']
    //         ]
    //     },
    //     theme: 'snow'
    // });
    //
    // quill.on("text-change", function () {
    //     $("#editor_quil").val(quill.root.innerHTML);
    // });

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
