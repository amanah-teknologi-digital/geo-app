$(document).ready(function () {
    tinymce.init({
        selector: '#editor_surat',
        plugins: 'link image code table lists wordcount',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | image',
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

    $.validator.addMethod("maxTotalSize", function(value, element, param) {
        // Pastikan ada file
        if (element.files.length === 0) return true;

        let totalSize = 0;
        for (let i = 0; i < element.files.length; i++) {
            totalSize += element.files[i].size;
        }

        // Bandingkan dengan limit (dalam byte)
        return totalSize <= param;
    }, function(param, element) {
        const sizeMB = (param / (1024 * 1024)).toFixed(2);
        return `Total file size must not exceed ${sizeMB} MB.`;
    });

    $.validator.addMethod("fileextension", function(value, element, param) {
        if(element.files.length === 0){
            return true;
        }
        // Dapatkan nama file dan ekstrak ekstensi
        var fileName = element.files[0].name;
        var extension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
        return $.inArray(extension, param) !== -1;
    }, "Tipe file tidak diperbolehkan.");

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

    $("#frmSetujuiPengajuan").validate({
        rules: {
            "filesurat[]": {
                fileextension: ['pdf', 'PDF'],
                maxTotalSize: 5242880 // 5MB dalam byte
            }
        },
        messages: {
            "filesurat[]": {
                fileextension: "Hanya file PDF yang diizinkan.",
                maxTotalSize: "Total akumulasi ukuran file harus kurang dari 5 MB."
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#frmUploadFile").validate({
        ignore: [],
        rules: {
            "filesuratupload[]": {
                required: true,
                fileextension: ['pdf', 'PDF'],
                maxTotalSize: 5242880 // 5MB dalam byte
            }
        },
        messages: {
            "filesuratupload[]": {
                required: "File tidak boleh kosong.",
                fileextension: "Hanya file PDF yang diizinkan.",
                maxTotalSize: "Total akumulasi ukuran file harus kurang dari 5 MB."
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "filesuratupload[]") {
                error.appendTo("#error-uploadfile");
            } else {
                // Default: tampilkan setelah elemen
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $.validator.addMethod("ratingRequired", function(value, element) {
        return $('input[name="rating"]:checked').length > 0;
    }, "Please select a rating.");

    $("#FrmSurveyKepuasan").validate({
        // Aturan validasi
        rules: {
            rating: {
                ratingRequired: true // Cukup gunakan 'required' bawaan
            }
        },
        // Pesan error yang akan ditampilkan
        messages: {
            rating: {
                // Pesan untuk aturan 'required'
                ratingRequired: "Mohon pilih bintang sebagai rating."
            }
        },
        // Mengatur di mana pesan error akan ditempatkan
        errorPlacement: function(error, element) {
            // Khusus untuk input 'rating', tampilkan error di div #error-rating
            if (element.attr("name") === "rating") {
                error.appendTo("#error-rating");
            } else {
                // Untuk input lain, gunakan penempatan default
                error.insertAfter(element);
            }
        },
        // Fungsi yang dijalankan jika form valid
        submitHandler: function(form) {
            // Kirim form jika semua validasi terpenuhi
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
                    let listpersetujuan = response.pihakpenyetujusurat;

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

    $('#modal-ajukan').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_ajukan'); // Ambil nilai data-id
        $('#id_akses_ajukan').val(dataId); // Masukkan ke modal
    });

    $('#modal-hapusfile').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_file'); // Ambil nilai data-id
        $('#id_filehapus').val(dataId); // Masukkan ke modal
    });

    $('#modal-setujui').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_setujui'); // Ambil nilai data-id
        var dataid_pihakpenyetuju = button.data('id_pihakpenyetuju'); // Ambil nilai data-id
        $('#id_akses_setujui').val(dataId); // Masukkan ke modal
        $('#id_pihakpenyetuju_setujui').val(dataid_pihakpenyetuju); // Masukkan ke modal
    });

    $('#modal-revisi').on('show.bs.modal', function(event) {
        $('#keteranganrev').html("");
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_revisi'); // Ambil nilai data-id
        var dataid_pihakpenyetuju = button.data('id_pihakpenyetuju'); // Ambil nilai data-id
        $('#id_akses_revisi').val(dataId); // Masukkan ke modal
        $('#id_pihakpenyetuju_revisi').val(dataid_pihakpenyetuju); // Masukkan ke modal
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
        var dataid_pihakpenyetuju = button.data('id_pihakpenyetuju'); // Ambil nilai data-id

        $('#id_akses_tolak').val(dataId); // Masukkan ke modal
        $('#id_pihakpenyetuju_tolak').val(dataid_pihakpenyetuju); // Masukkan ke modal
    });
})
