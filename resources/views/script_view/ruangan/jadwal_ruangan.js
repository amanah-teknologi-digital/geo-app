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
            lantai: {
                required: true,
                number: true
            },
            kapasitas: {
                required: true,
                number: true
            },
            deskripsi: {
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
            lantai: {
                required: "Lantai ruangan wajib diisi",
                number: "Lantai harus berupa angka"
            },
            kapasitas: {
                required: "Kapasitas ruangan wajib diisi",
                number: "Kapasitas harus berupa angka"
            },
            deskripsi: {
                required: "Deskripsi ruangan wajib diisi"
            },
            keterangan: {
                required: "Keterangan ruangan wajib diisi"
            },
            gambar_ruangan: {
                filesize: "Ukuran file maksimal 5 MB",
                fileextension: "Hanya file JPG, JPEG, PNG yang diperbolehkan"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
})
