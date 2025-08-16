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

    formValidation = $("#frmPengajuanRuang").validate({
        rules: {
            pemeriksa_awal: {
                required: true
            },
        },
        messages: {
            pemeriksa_awal: {
                required: "Pemeriksa awal harus ditentukan!"
            }
        },
        errorPlacement: function(error, element) {
            // Menentukan lokasi error berdasarkan id atau atribut lain
            if (element.attr("name") === "pemeriksa_awal") {
                error.appendTo("#error-pemeriksa_awal");
            } else {
                // Default: tampilkan setelah elemen
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
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
