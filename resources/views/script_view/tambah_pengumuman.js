import Quill from 'quill';
import 'quill/dist/quill.snow.css';
$(document).ready(function () {
    const fullToolbar = [
        [
            {
                font: []
            },
            {
                size: []
            }
        ],
        ['bold', 'italic', 'underline', 'strike'],
        [
            {
                color: []
            },
            {
                background: []
            }
        ],
        [
            {
                script: 'super'
            },
            {
                script: 'sub'
            }
        ],
        [
            {
                header: '1'
            },
            {
                header: '2'
            },
            'blockquote',
            'code-block'
        ],
        [
            {
                list: 'ordered'
            },
            {
                list: 'bullet'
            },
            {
                indent: '-1'
            },
            {
                indent: '+1'
            }
        ],
        [
            'direction',
            {
                align: []
            }
        ],
        ['link', 'image', 'video', 'formula'],
        ['clean']
    ];

    const quill = new Quill('#editor_pengumuman', {
        bounds: '#full-editor',
        modules: {
            formula: true,
            toolbar: fullToolbar
        },
        theme: 'snow'
    });

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

    $("#formPengumuman").validate({
        rules: {
            judul: {
                required: true
            },
            gambar_header: {
                filesize: 5242880,
                fileextension: ['jpg', 'jpeg', 'png', 'gif']
            }
        },
        messages: {
            judul: {
                required: "Judul wajib diisi"
            },
            gambar_header: {
                filesize: "Ukuran file maksimal 5 MB",
                fileextension: "Hanya file JPG, JPEG, PNG, GIF, dan PDF yang diperbolehkan"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
})
