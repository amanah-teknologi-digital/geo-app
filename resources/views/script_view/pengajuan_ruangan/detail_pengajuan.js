let formValidation;

$(document).ready(function () {
    $('#pemeriksa_awal').select2({
        theme: 'bootstrap-5',
        placeholder: 'Cari user...',
        width: '100%',
        ajax: {
            url: urlGetUser,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                formValidation.focusInvalid();
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

    $('#pemeriksa_akhir').select2({
        theme: 'bootstrap-5',
        placeholder: 'Cari user...',
        width: '100%',
        ajax: {
            url: urlGetUser,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                formValidation.focusInvalid();
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

    $.validator.addMethod("filesize", function(value, element, param) {
        var isValid = true;
        if (element.files.length > 0) {
            for (let i = 0; i < element.files.length; i++) {
                if (element.files[i].size > param) {
                    isValid = false;
                    break;
                }
            }
        }
        return isValid;
    }, "Ukuran file maksimal 5 MB");

    $.validator.addMethod("minfiles", function(value, element, param) {
        return element.files.length >= param;
    }, "Harus upload minimal {0} file.");

    $.validator.addMethod("extension", function (value, element, param) {
        if (value) {
            var pattern = new RegExp("\\.(" + param + ")$", "i");
            return pattern.test(value);
        }
        return true;
    }, "Format file tidak valid.");

    formValidation = $("#frmPengajuanRuang").validate({
        rules: {
            pemeriksa_awal: {
                required: true
            },
            "filesesudahacara[]": {
                extension: "jpg|jpeg|png|webp", // hanya tipe ini
                filesize: 5 * 1024 * 1024, // 5MB
                minfiles: 5,
            },
            pemeriksa_akhir: {
                required: true
            }
        },
        messages: {
            pemeriksa_awal: {
                required: "Pemeriksa awal harus ditentukan!"
            },
            "filesesudahacara[]": {
                extension: "Hanya file JPG, JPEG, PNG, atau WEBP yang diperbolehkan.",
                minfiles: "Silakan unggah minimal 5 foto sesudah acara."
            },
            pemeriksa_akhir: {
                required: "Pemeriksa akhir harus ditentukan!"
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.is(":radio")) {
                // kalau radio, taruh pesan di container parent
                error.insertAfter(element.closest(".d-flex"));
            }else if (element.attr("name") === "pemeriksa_awal") {
                error.appendTo("#error-pemeriksa_awal");
            }else if (element.attr("name") === "pemeriksa_akhir") {
                error.appendTo("#error-pemeriksa_akhir");
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

    $(document).on("click", "#btn-ajukan", function (e) {
        e.preventDefault(); // cegah modal langsung muncul

        if ($("#frmPengajuanRuang").valid()) {
            // // kalau valid → buka modal
            var dataId = $(this).data('id_akses_ajukan');
            var tahapanNext = $(this).data('tahapan_next'); // Ambil nilai data-id
            $('#id_aksespersetujuan').val(dataId);
            $('#tahapan_next').val(tahapanNext);

            $("#modal-ajukan").modal("show");
        } else {
            // kalau tidak valid → fokus ke field pertama error
            formValidation.focusInvalid();
        }
    });

    $(document).on("click", "#btn-setujui", function (e) {
        e.preventDefault(); // cegah modal langsung muncul

        if ($("#frmPengajuanRuang").valid()) {
            // // kalau valid → buka modal
            var dataId = $(this).data('id_akses_ajukan');
            var tahapanNext = $(this).data('tahapan_next'); // Ambil nilai data-id
            $('#id_aksespersetujuan').val(dataId);
            $('#tahapan_next').val(tahapanNext);

            $("#modal-setujui").modal("show");
        } else {
            // kalau tidak valid → fokus ke field pertama error
            formValidation.focusInvalid();
        }
    });

    $(document).on("click", "#btn-ajuanconfirm", function (e) {
        $("#frmPengajuanRuang").submit();
    });

    $(document).on("click", "#btn-setujuiconfirm", function (e) {
        $("#frmPengajuanRuang").submit();
    });

    $('#modal-hapusfile').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_file'); // Ambil nilai data-id
        $('#id_filehapus').val(dataId); // Masukkan ke modal
    });

    $('#modal-tolak').on('show.bs.modal', function(event) {
        $('#keterangantolak').html("");
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id_akses_tolak'); // Ambil nilai data-id

        $('#id_akses_tolak').val(dataId); // Masukkan ke modal
    });
});
