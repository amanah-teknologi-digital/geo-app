$(document).ready(function () {
    // Custom method untuk validasi ukuran file (misal max 2MB)
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

    $("#formPengaturanLandingPage").validate({
        rules: {
            alamat: {
                required: true
            },
            admin_geoletter: {
                required: true
            },
            admin_ruang: {
                required: true
            },
            admin_peralatan: {
                required: true
            },
            link_yt: {
                required: true
            },
            link_fb: {
                required: true
            },
            link_ig: {
                required: true
            },
            link_linkedin: {
                required: true
            },
            file_sop_geoletter: {
                filesize: 10485760,
                fileextension: ['jpg', 'jpeg', 'png', 'gif', 'pdf']
            },
            file_sop_georoom: {
                filesize: 10485760,
                fileextension: ['jpg', 'jpeg', 'png', 'gif', 'pdf']
            },
            file_sop_geofacility: {
                filesize: 10485760,
                fileextension: ['jpg', 'jpeg', 'png', 'gif', 'pdf']
            }
        },
        messages: {
            alamat: {
                required: "Alamat wajib diisi"
            },
            admin_geoletter: {
                filesize: "Data admin geo letter wajib diisi"
            },
            admin_ruang: {
                filesize: "Data admin geo ruangan wajib diisi"
            },
            admin_peralatan: {
                filesize: "Data admin geo peralatan wajib diisi"
            },
            link_yt: {
                required: "Link youtube wajib diisi"
            },
            link_fb: {
                required: "Link facebook wajib diisi"
            },
            link_ig: {
                required: "Link instagram wajib diisi"
            },
            link_linkedin: {
                required: "Link linkedin wajib diisi"
            },
            file_sop_geoletter: {
                filesize: "Ukuran file maksimal 10 MB",
                fileextension: "Hanya file JPG, JPEG, PNG, GIF, dan PDF yang diperbolehkan"
            },
            file_sop_georoom: {
                filesize: "Ukuran file maksimal 10 MB",
                fileextension: "Hanya file JPG, JPEG, PNG, GIF, dan PDF yang diperbolehkan"
            },
            file_sop_geofacility: {
                filesize: "Ukuran file maksimal 10 MB",
                fileextension: "Hanya file JPG, JPEG, PNG, GIF, dan PDF yang diperbolehkan"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
})
