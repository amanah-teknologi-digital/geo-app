$(document).ready(function () {
    $.validator.addMethod("filesize", function(value, element, param) {
        // Cek jika file dipilih
        if(element.files.length === 0) {
            return true;
        }
        // Ukuran file dalam bytes
        return element.files[0].size <= param;
    }, "Ukuran file terlalu besar.");

    // Custom method untuk validasi tipe file (misal hanya jpg dan png)
    $.validator.addMethod("fileextension", function(value, element, param) {
        if(element.files.length === 0){
            return true;
        }
        // Dapatkan nama file dan ekstrak ekstensi
        var fileName = element.files[0].name;
        var extension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
        return $.inArray(extension, param) !== -1;
    }, "Tipe file tidak diperbolehkan.");

    $("#formRuangan").validate({
        rules: {
            kode_ruangan: {
                required: true
            },
            nama_ruangan: {
                required: true
            },
            jenis_ruangan: {
                required: true
            },
            lokasi: {
                required: true
            },
            kapasitas: {
                required: true,
                number: true
            },
            fasilitas: {
                required: true
            },
            keterangan: {
                required: true
            },
            gambar_ruangan: {
                filesize: 5242880,
                fileextension: ['jpg', 'jpeg', 'png', 'gif']
            }
        },
        messages: {
            kode_ruangan: {
                required: "Kode ruangan wajib diisi"
            },
            nama_ruangan: {
                required: "Nama ruangan wajib diisi"
            },
            jenis_ruangan: {
                required: "Jenis ruangan wajib diisi"
            },
            lokasi: {
                required: "Lokasi ruangan wajib diisi"
            },
            kapasitas: {
                required: "Kapasitas ruangan wajib diisi",
                number: "Kapasitas harus berupa angka"
            },
            fasilitas: {
                required: "Fasilitas ruangan wajib diisi"
            },
            keterangan: {
                required: "Keterangan ruangan wajib diisi"
            },
            gambar_ruangan: {
                filesize: "Ukuran file maksimal 5 MB",
                fileextension: "Hanya file JPG, JPEG, PNG yang diperbolehkan"
            }
        },
        errorPlacement: function(error, element) {
        // Menentukan lokasi error berdasarkan id atau atribut lain
        if (element.attr("name") === "fasilitas") {
            error.appendTo("#error-fasilitas");
        } else {
            // Default: tampilkan setelah elemen
            error.insertAfter(element);
        }
    },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $('#fasilitas').select2({
        width: '100%',
        templateResult: formatFasilitas,
        templateSelection: formatFasilitas
    });

    // Fungsi untuk format hasil pilih dengan ikon
    function formatFasilitas(facility) {
        if (!facility.id) return facility.text; // Menampilkan hanya teks jika tidak ada ID
        var $span = $('<span><i class="bx ' + $(facility.element).data('icon') + '"></i> ' + facility.text + '</span>');
        return $span;
    }
})
