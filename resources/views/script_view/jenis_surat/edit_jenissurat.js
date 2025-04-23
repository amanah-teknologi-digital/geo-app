$(document).ready(function () {
    tinymce.init({
        selector: '#editor',
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

    $("#formJenisSurat").validate({
        ignore: "",
        rules: {
            nama_jenis: {
                required: true
            },
            editor: {
                required: true
            }
        },
        messages: {
            nama_jenis: {
                required: "Nama jenis surat wajib diisi"
            },
            editor: {
                required: "Template tidak boleh kosong"
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "editor") {
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
