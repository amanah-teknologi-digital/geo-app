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
        },
        automatic_uploads: true,
        images_upload_url: routeUploadGambarTinymce,
        file_picker_types: 'image',
        file_picker_callback: function (cb, value, meta) {
            // Hanya untuk gambar
            if (meta.filetype === 'image') {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*'); // hanya gambar

                input.onchange = function () {
                    const file = this.files[0];

                    // Opsional: validasi manual di sisi klien
                    if (!file.type.startsWith('image/')) {
                        alert('Hanya file gambar yang diperbolehkan.');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('id_user', idUser); // sesuaikan
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                    fetch(routeUploadGambarTinymce, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.location) {
                                cb(data.location, { title: file.name }); // WAJIB ini!
                            } else {
                                alert('Upload gagal: location tidak ditemukan.');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Upload error: ' + error.message);
                        });
                };

                input.click();
            }
        },
        images_upload_handler: function (blobInfo, success, failure) {
            const formData = new FormData();
            formData.append('file', blobInfo.blob());
            formData.append('id_user', idUser);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            return fetch(routeUploadGambarTinymce, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);  // Debugging
                    let jsonObject = JSON.parse(data);
                    let jsonArray = data.entries(jsonObject);
                    console.log(jsonArray)

                    if (data.location && data.location.indexOf('http') !== -1) {  // Cek URL valid
                        success(data.location);  // Kembali ke TinyMCE dengan URL gambar
                    } else {
                        failure('Upload failed: No valid location returned');
                    }
                })
                .catch(error => {
                    failure('Upload error: ' + error.message);
                });
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
