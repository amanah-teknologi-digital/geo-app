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

                if (!isEdit){
                    editor.getBody().setAttribute('contenteditable', false);
                }
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
            id_pengajuan:{
                required: true
            },
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
            id_pengajuan: {
                required: "Id Pengajuan wajib diisi"
            },
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

        if (id_jenissurat) {
            $.ajax({
                url: routeGetJenisSurat,
                type: 'GET',
                data: { id_jenissurat: id_jenissurat },
                dataType: 'json',
                success: function(response) {
                    tinymce.get('editor_surat').setContent(response.default_form);
                },
                error: function(xhr, status, error) {
                    tinymce.get('editor_surat').setContent('');
                }
            });
        } else {
            tinymce.get('editor_surat').setContent('');
        }
    });

    $('#modal-ajukan').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_ajukan'); // Ambil nilai data-id
        $('#id_akses_ajukan').val(dataId); // Masukkan ke modal
    });

    $('#modal-setujui').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_setujui'); // Ambil nilai data-id
        $('#id_akses_setujui').val(dataId); // Masukkan ke modal
    });

    $('#modal-revisi').on('show.bs.modal', function(event) {
        $('#keteranganrev').html("");
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_revisi'); // Ambil nilai data-id
        $('#id_akses_revisi').val(dataId); // Masukkan ke modal
    });

    $('#modal-sudahrevisi').on('show.bs.modal', function(event) {
        $('#keterangansudahrev').html("");
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_sudahrevisi'); // Ambil nilai data-id
        $('#id_akses_sudahrevisi').val(dataId); // Masukkan ke modal
    });

    $('#modal-tolak').on('show.bs.modal', function(event) {
        $('#keterangantolak').html("");
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_tolak'); // Ambil nilai data-id
        $('#id_akses_tolak').val(dataId); // Masukkan ke modal
    });
})
