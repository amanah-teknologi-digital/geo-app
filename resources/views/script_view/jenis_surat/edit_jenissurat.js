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

    $("#formJenisSurat").validate({
        ignore: "",
        rules: {
            nama_jenis: {
                required: true
            },
            keterangan_datadukung: {
                required: {
                    depends: function () {
                        return $('#flexSwitchCheckChecked').is(':checked');
                    }
                }
            },
            editor: {
                required: true
            }
        },
        messages: {
            nama_jenis: {
                required: "Nama jenis surat wajib diisi"
            },
            keterangan_datadukung: {
                required: "Harap isi keterangan data pendukung"
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

    // Handler ketika checkbox berubah
    $('#flexSwitchCheckChecked').on('change', function() {
        if ($(this).is(':checked')) {
            $('#div_keterangan_datadukung').show();
        } else {
            $('#div_keterangan_datadukung').hide();
        }
    });

    $('#user_penyetuju').select2({
        placeholder: 'Cari user...',
        width: '100%',
        dropdownParent: $('#modal-tambahpersetujuan'),
        ajax: {
            url: urlGetUser,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term, id_jenissurat: idJenisSurat };
            },
            processResults: function (data) {
                return {
                    results: data.map(user => ({
                        id: user.id,
                        text: user.name
                    }))
                };
            },
            cache: true
        }
    });

    $("#frm_tambahpersetujuan").validate({
        ignore: "",
        rules: {
            nama_persetujuan: {
                required: true
            },
            user_penyetuju: {
                required: true
            }
        },
        messages: {
            nama_persetujuan: {
                required: "Nama persetujuan wajib diisi"
            },
            user_penyetuju: {
                required: "User penyetuju wajib dipilih"
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "user_penyetuju") {
                error.appendTo("#error-user_penyetuju");
            } else {
                // Default: tampilkan setelah elemen
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $('#modal-hapuspersetujuan').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_pihakpenyetuju'); // Ambil nilai data-id
        $('#id_pihakpenyetujusurat').val(dataId); // Masukkan ke modal
    });
});
