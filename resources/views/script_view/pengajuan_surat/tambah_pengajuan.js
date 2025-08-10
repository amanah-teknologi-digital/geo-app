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
                    let isDataPendukung = response.is_datapendukung;
                    let namaDataPendukung = response.nama_datapendukung

                    tinymce.get('editor_surat').setContent(response.default_form);

                    listpersetujuan.sort((a, b) => a.urutan - b.urutan);
                    // Buat daftar nama dengan urutan
                    let list2 = '<span class="badge bg-primary text-white">1. Admin ' + namaLayananSurat + '</span>';

                    let list = listpersetujuan.map(item => `<span class="badge bg-primary text-white">${item.urutan}. ${item.nama}</span>`);

                    if (listpersetujuan.length > 0){
                        list2 += ' <i class="bx bx-arrow-back text-primary" style="transform: rotate(180deg);"></i> ';
                        list2 += list.join(' <i class="bx bx-arrow-back text-primary" style="transform: rotate(180deg);"></i>');
                    }

                    // Gabungkan dengan tanda panah → dan tampilkan
                    $('#list-persetujuan').html(list2);

                    if (isDataPendukung === 1){
                        $('#div_datapendukung').show();
                        $('#nama_datapendukung').html("("+namaDataPendukung+")");
                        $('#data_pendukung').attr('required', true);
                    }else{
                        $('#div_datapendukung').hide();
                        $('#nama_datapendukung').html('');
                        $('#data_pendukung').val('');
                        $('#data_pendukung').removeAttr('required');
                    }
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
