$(document).ready(function () {
    tinymce.init({
        selector: '#editor_surat',
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

    $("#formPengajuan").validate({
        ignore: "",
        rules: {
            jenis_surat: {
                required: true
            },
            editor_surat: {
                required: true
            },
            keterangan: {
                required: true
            }
        },
        messages: {
            jenis_surat: {
                required: "Jenis surat wajib diisi"
            },
            editor_surat: {
                required: "Surat tidak boleh kosong"
            },
            keterangan: {
                required: "Keterangan wajib diisi"
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "editor_surat") {
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
        $('#list-persetujuan').html('-');

        if (id_jenissurat) {
            $.ajax({
                url: routeGetJenisSurat,
                type: 'GET',
                data: { id_jenissurat: id_jenissurat },
                dataType: 'json',
                success: function(response) {
                    let listpersetujuan = response.pihakpenyetujusurat;

                    tinymce.get('editor_surat').setContent(response.default_form);

                    listpersetujuan.sort((a, b) => a.urutan - b.urutan);
                    // Buat daftar nama dengan urutan
                    let list = listpersetujuan.map(item => `${item.urutan}. ${item.nama} <i class="text-success">(${item.userpenyetuju.name})</i>`);

                    // Gabungkan dengan tanda panah → dan tampilkan
                    $('#list-persetujuan').html(list.join(' &rarr; '));
                },
                error: function(xhr, status, error) {
                    tinymce.get('editor_surat').setContent('');
                }
            });
        } else {
            tinymce.get('editor_surat').setContent('');
        }
    });
})
