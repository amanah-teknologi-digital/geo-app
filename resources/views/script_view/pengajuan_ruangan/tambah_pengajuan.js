let stepper;
let eventsData = [];
let calendar;
let formValidationEL;
let formValidation;
let stepperEl;
let instanceJadwal, instanceJamMulai, instanceJamSelesai;
let toast;
let nomorPeralatan;
let dataRuangan = [
    {
        "id_ruangan": "4AB09A82-2901-4791-9695-CDDD51037631",
        "gambar_file": "E06A013D-DBA0-416E-8828-3F634660B907",
        "kode_ruangan": "TG-302",
        "nama": "Ruangan TG-302",
        "jenis_ruangan": "Ruang Kuliah",
        "fasilitas": "[\n    {\n        \"id\": \"meja_dosen\",\n        \"text\": \"Meja Dosen\",\n        \"icon\": \"bx-user-voice\"\n    },\n    {\n        \"id\": \"charger\",\n        \"text\": \"Charging Station\",\n        \"icon\": \"bx-battery\"\n    },\n    {\n        \"id\": \"meja_lab\",\n        \"text\": \"Meja Lab Tahan Kimia\",\n        \"icon\": \"bx-lock\"\n    },\n    {\n        \"id\": \"dispenser\",\n        \"text\": \"Dispenser \\/ Galon\",\n        \"icon\": \"bx-droplet\"\n    },\n    {\n        \"id\": \"reservasi_digital\",\n        \"text\": \"Reservasi Digital\",\n        \"icon\": \"bx-calendar\"\n    }\n]",
        "kapasitas": 50,
        "lokasi": "Gedung teknik geofisika lt 2",
        "is_aktif": 1,
        "keterangan": "Ruangan ber-AC, dilengkapi proyektor dan sound system. Cocok untuk kegiatan rapat, pelatihan, atau seminar dengan kapasitas maksimal 50 orang.",
        "created_at": "2025-04-21T05:27:51.000000Z",
        "updated_at": "2025-04-23T03:52:17.000000Z",
        "updater": 24
    },
    {
        "id_ruangan": "DA08FCE9-7AFB-4425-9AF1-963F9C1A31B8",
        "gambar_file": "A15E0551-FE09-4E49-80E0-09BB7B09F980",
        "kode_ruangan": "TG-304",
        "nama": "Ruangan TG-304",
        "jenis_ruangan": "Ruang Kuliah",
        "fasilitas": "[\n    {\n        \"id\": \"kursi_mahasiswa\",\n        \"text\": \"Kursi Mahasiswa\",\n        \"icon\": \"bx-chair\"\n    },\n    {\n        \"id\": \"meja_mahasiswa\",\n        \"text\": \"Meja Mahasiswa\",\n        \"icon\": \"bx-table\"\n    },\n    {\n        \"id\": \"whiteboard\",\n        \"text\": \"Whiteboard \\/ Papan Tulis\",\n        \"icon\": \"bx-edit-alt\"\n    },\n    {\n        \"id\": \"karpet\",\n        \"text\": \"Karpet \\/ Lantai\",\n        \"icon\": \"bx-grid-alt\"\n    },\n    {\n        \"id\": \"monitor\",\n        \"text\": \"LCD Monitor\",\n        \"icon\": \"bx-desktop\"\n    },\n    {\n        \"id\": \"wifi\",\n        \"text\": \"Wi-Fi \\/ LAN\",\n        \"icon\": \"bx-wifi\"\n    },\n    {\n        \"id\": \"speaker\",\n        \"text\": \"Speaker \\/ Sound System\",\n        \"icon\": \"bx-speaker\"\n    },\n    {\n        \"id\": \"mikrofon\",\n        \"text\": \"Mikrofon\",\n        \"icon\": \"bx-microphone\"\n    },\n    {\n        \"id\": \"cctv\",\n        \"text\": \"Kamera CCTV\",\n        \"icon\": \"bx-camera-home\"\n    },\n    {\n        \"id\": \"apar\",\n        \"text\": \"APAR (Pemadam Api)\",\n        \"icon\": \"bxs-hot\"\n    },\n    {\n        \"id\": \"rfid\",\n        \"text\": \"Kartu Akses \\/ RFID\",\n        \"icon\": \"bx-id-card\"\n    }\n]",
        "kapasitas": 100,
        "lokasi": "Gedung Teknik Geofisika ITS lt 2",
        "is_aktif": 1,
        "keterangan": "1. Ruangan hanya boleh digunakan sesuai waktu yang telah disepakati dalam jadwal sewa.\r\n2. Penyewa wajib menjaga kebersihan dan kerapihan ruangan selama dan setelah penggunaan.\r\n3. Dilarang merokok, membawa makanan berat, atau minuman beralkohol ke dalam ruangan.\r\n4. Fasilitas di dalam ruangan (AC, proyektor, meja, kursi, dll) harus digunakan dengan hati-hati.\r\n5. Jika terjadi kerusakan pada fasilitas selama masa sewa, penyewa wajib bertanggung jawab atas penggantiannya.\r\n6. Penggunaan ruangan di luar waktu yang ditentukan akan dikenakan biaya tambahan.\r\n7. Penyewa wajib meninggalkan ruangan dalam kondisi seperti semula.\r\n8. Pembatalan sewa minimal 1 hari sebelum waktu penggunaan. Pembatalan mendadak akan tetap dikenakan biaya sewa.",
        "created_at": "2025-04-23T03:29:52.000000Z",
        "updated_at": "2025-04-23T10:35:49.000000Z",
        "updater": 24
    }
];

$(document).ready(function () {
    const toastLive = document.getElementById('liveToast')
    toast = new bootstrap.Toast(toastLive)

    stepperEl = document.getElementById('wizard');
    formValidationEL = $('#wizard-validation');
    stepper = new Stepper(stepperEl, { linear: true, animation: true });
    showStep1()
    hiddenStep2()
    validasiForm();
    stepperHandler();
    inisiasiCalendar();
    I();
    filterHandler();
    instanceJadwal =  inisiasiTanggal("#tanggal_booking");
    instanceJamMulai = inisiasiJamMulai('jam_mulai', 'jam_selesai');
    instanceJamSelesai = inisiasiJamSelesai('jam_mulai', 'jam_selesai');

    $('#ruangan').on('change', function() {
        let value = $(this).val();
        instanceJadwal.clear();
        instanceJamMulai.clear();
        instanceJamSelesai.clear();

        if (value){
            getDataJadwal();
        }else{
            eventsData = [];
            loadFilteredEvents();
        }
    });

    $('#ruangan').select2({width:'100%'})
});

function filterHandler(){
    document.querySelectorAll('[data-bs-toggle="sidebar"]').forEach((function (e) {
            e.addEventListener("click", (function () {
                    var t = e.getAttribute("data-target")
                        , n = e.getAttribute("data-overlay")
                        , o = document.querySelectorAll(".app-overlay");
                    document.querySelectorAll(t).forEach((function (e) {
                            e.classList.toggle("show"),
                            null != n && !1 !== n && void 0 !== o && (e.classList.contains("show") ? o[0].classList.add("show") : o[0].classList.remove("show"),
                                o[0].addEventListener("click", (function (t) {
                                        t.currentTarget.classList.remove("show"),
                                            e.classList.remove("show")
                                    }
                                )))
                        }
                    ))
                }
            ))
        }
    ));
}

function stepperHandler(){
    // Tombol custom (jika dipakai)
    $('#btn-next-1').click(() => {
        if (formValidation.form()){
            stepper.to(2);
            calendar.updateSize();
            setTimeout(function() {
                showStep2();
            }, 100);
        }
    });

    $('#btn-prev-1').click(() => {
        stepper.to(1)
        hiddenStep2();
        resetErrorForm();
    });

    $('#btn-next-2').click(() => {
        if (formValidation.form()){
            stepper.to(3)
            $('#tabelPeminjaman').html("");
            nomorPeralatan = 2;
            generateTablePeminjaman();
        }
    });

    $('#btn-prev-2').click(() => {
        stepper.to(2)
        calendar.updateSize();
        resetErrorForm();
    });
}

function resetErrorForm(){
    formValidation.resetForm();
    formValidationEL.find(".error").removeClass("error");
}

function hiddenStep1(){
    $('#status_peminjam').hide();
}

function hiddenStep2(){
    $('#ruangan').hide();
    $('#tanggal_booking').hide();
    $('#jam_mulai').hide();
    $('#jam_selesai').hide();
}

function showStep1(){
    $('#status_peminjam').show();
}

function showStep2(){
    $('#ruangan').show();
    $('#tanggal_booking').show();
    $('#jam_mulai').show();
    $('#jam_selesai').show();
}

function validasiForm() {
    $.validator.addMethod("time24", function(value, element) {
        return this.optional(element) || /^([01]?[0-9]|2[0-3]):([0-5][0-9])$/.test(value); // Format 24 jam: HH:mm
    }, "Please enter a valid time in 24-hour format (HH:mm).");

    formValidation = formValidationEL.validate({
        rules: {
            status_peminjam: {
                required: false
            },
            'ruangan[]': {
                required: false
            },
            tanggal_booking: {
                required: false
            },
            jam_mulai: {
                required: false,
                time24: false
            },
            jam_selesai: {
                required: false,
                time24: false
            }
        },
        messages: {
            status_peminjam: {
                required: "Status peminjam wajib diisi."
            },
            'ruangan[]': {
                required: "Ruangan wajib diisi."
            },
            tanggal_booking: {
                required: "Tanggal booking wajib diisi."
            },
            jam_mulai: {
                required: "Jam mulai wajib diisi.",
                time24: "Waktu tidak valid."
            },
            jam_selesai: {
                required: "Jam selesai wajib diisi.",
                time24: "Waktu tidak valid."
            }
        },
        ignore: ":hidden",
        errorPlacement: function (error, element) {
            if (element.attr("name") === "jam_mulai") {
                error.appendTo("#error-jammulai");
            }else if (element.attr("name") === "jam_selesai") {
                error.appendTo("#error-jamselesai");
            }else if (element.attr("name") === "ruangan[]") {
                error.appendTo("#error-ruangan");
            } else {
                // Default: tampilkan setelah elemen
                error.insertAfter(element);
            }
        }
    });
}

function inisiasiCalendar() {
    const calendarEl = document.getElementById('calendar');
    calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timegridPlugin, listPlugin],
        initialView: 'dayGridMonth',
        height: '80%',
        editable: !0,
        dragScroll: !0,
        eventResizableFromStart: !0,
        customButtons: {
            sidebarToggle: {
                text: ""
            }
        },
        locale: 'id',
        dayMaxEvents: 2,
        headerToolbar: {
            start: "sidebarToggle, prev,next, title",
            end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
        },
        events: eventsData,
        eventClick: function(info) {
            const start = info.event.start;
            const end = info.event.end;

            const formatDateTime = (date) => {
                if (!date) return '';
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${day}/${month}/${year} ${hours}:${minutes}`;
                // Kalau mau tahun di depan, ganti jadi: `${year}-${month}-${day} ${hours}:${minutes}`;
            };

            const type = info.event.extendedProps?.type || '';

            // Tambahkan (booking) ke judul kalau type booking
            const namaruangan = info.event.extendedProps.nama_ruangan;
            const title = info.event.title + (type === 'booking' ? ' (booking)' : '');

            $('#eventModalNamaRuangan').text(namaruangan);
            $('#eventModalTitle').text(title);
            $('#eventModalStart').text(formatDateTime(start));
            $('#eventModalEnd').text(formatDateTime(end));
            $('#eventModal').modal('show');

        },
        eventClassNames: function({ event }) {
            return [
                'rounded-2', 'p-1', 'px-2', 'fw-semibold', 'small', 'text-nowrap',
                'bg-label-' + (event.extendedProps.calendar || 'primary'),  // default ke primary
                'text-truncate text-' + (event.extendedProps.calendar || 'primary')       // text color ikut calendar
            ];
        },
        eventContent: function(arg) {
            const start = arg.event.start;
            const end = arg.event.end;

            const formatTime = (date) => {
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${hours}:${minutes}`;
            };

            const startTime = start ? formatTime(start) : '';
            const endTime = end ? formatTime(end) : '';
            const timeRange = (startTime && endTime) ? `${startTime} - ${endTime}` : (startTime || '');
            const typeLabel = (arg.event.extendedProps?.type === 'booking') ? ' (booking)' : '';

            return {
                html: `
                   <div>
                        ${arg.event.extendedProps.nama_ruangan} - ${arg.event.title}${typeLabel}:${timeRange ? ` <span>${timeRange} </span>` : ''}
                   </div>`
            };
        }
    });

    calendar.render();
}

function I() {
    var e = document.querySelector(".fc-sidebarToggle-button");
    if (e) {
        e.classList.remove("fc-button-primary");
        e.classList.add("d-lg-none", "d-inline-block", "ps-0");

        while (e.firstChild) {
            e.removeChild(e.firstChild);
        }

        e.setAttribute("data-bs-toggle", "sidebar");
        e.setAttribute("data-overlay", "");
        e.setAttribute("data-target", "#app-calendar-sidebar");
        e.insertAdjacentHTML("beforeend", '<i class="icon-base bx bx-menu icon-lg text-heading"></i>');
    }
}

function inisiasiTanggal(dom){
    return flatpickr(dom, {
        mode: "range",
        minDate: new Date().fp_incr(1), // today + 1 day
        locale: Indonesian,
        dateFormat: "d-m-Y",
        onChange: function(selectedDates, dateStr, instance) {
            // Jika ada dua tanggal yang dipilih, ubah 'to' menjadi 's/d'
            if (selectedDates.length === 0 || !selectedDates[0]) {
                return; // Jangan lakukan apa-apa kalau kosong
            }

            if (selectedDates.length === 2) {
                // Ubah teks di dalam input menjadi "Tanggal Mulai s/d Tanggal Selesai"
                let startDate = formatDate(selectedDates[0]);  // Format tanggal mulai
                let endDate = formatDate(selectedDates[1]);    // Format tanggal selesai

                instance.input.value = `${startDate} s/d ${endDate}`;
            }
        }
    });
}

function inisiasiJamMulai(dom_mulai, dom_selesai) {
    return flatpickr("#" + dom_mulai, {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        onChange: function (selectedDates, dateStr, instance) {
            if (selectedDates.length === 0 || !selectedDates[0]) {
                return; // Jangan lakukan apa-apa kalau kosong
            }
            // Set default waktu selesai menjadi waktu mulai
            let endTime = new Date(selectedDates[0].getTime());
            endTime.setMinutes(endTime.getMinutes() + 30);  // Waktu selesai default 30 menit setelah mulai
            document.getElementById(dom_selesai)._flatpickr.setDate(endTime); // Set waktu selesai otomatis
        }
    });
}

function inisiasiJamSelesai(dom_mulai, dom_selesai) {
    return flatpickr("#"+dom_selesai, {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 0 || !selectedDates[0]) {
                return; // Jangan lakukan apa-apa kalau kosong
            }

            let startTime = new Date(document.getElementById(dom_mulai)._flatpickr.selectedDates[0].getTime());
            let endTime = selectedDates[0];

            if (endTime < startTime) {
                alert("Jam selesai tidak boleh lebih awal dari jam mulai!");
                instance.clear(); // Hapus pilihan jika selesai < mulai
                $('#'+dom_mulai).val('');
            }
        }
    });
}

function getDataJadwal(){
    let idRuangan = $('#ruangan').val();

    if (idRuangan) {
        $.ajax({
            url: urlGetData,  // Ganti dengan URL API yang sesuai
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: {
                'id_ruangan': idRuangan
            },
            success: function (response) {
                eventsData = [
                    ...response.jadwal,  // Data jadwal
                    ...response.booking  // Data booking
                ];

                loadFilteredEvents();
            },
            error: function (xhr, status, error) {
                eventsData = [];
                $('#ruangan').val('')
                loadFilteredEvents();
            }
        });
    }else{
        instanceJadwal.clear();
        instanceJamMulai.clear();
        instanceJamSelesai.clear();
        eventsData = [];
        loadFilteredEvents();
    }
}

function checkAvaliableJadwal(){
    let idRuangan = $('#ruangan').val();
    let tanggalBooking = $('#tanggal_booking').val();
    let jamMulai = $('#jam_mulai').val();
    let jamSelesai = $('#jam_selesai').val();

    setLoadingButton('btn-next-2', true);
    $.ajax({
        url: urlCheckJadwalRuangan,  // Ganti dengan URL API yang sesuai
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            'id_ruangan': idRuangan,
            'tanggal_booking': tanggalBooking,
            'jam_mulai': jamMulai,
            'jam_selesai': jamSelesai
        },
        success: function(response) {
            let status = response.status;
            dataRuangan = response.dataRuangan;

            setLoadingButton('btn-next-2', false);
            if (status){
                stepper.to(3);
            }else{
                showToast('Jadwal bentrok dengan jadwal lain!', 'bg-danger')
            }
        },
        error: function(xhr, status, error) {
            setLoadingButton('btn-next-2', false);
            showToast('Error mendapatkan data!', 'bg-danger')
            instanceJadwal.clear();
            instanceJamMulai.clear();
            instanceJamSelesai.clear();
        }
    });
}

function loadFilteredEvents() {
    calendar.removeAllEvents();
    calendar.addEventSource(eventsData);
}

function formatDate(date) {
    let day = String(date.getDate()).padStart(2, '0');
    let month = String(date.getMonth() + 1).padStart(2, '0');  // Bulan dimulai dari 0
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

function showToast(keterangan, bgClassColor){
    const toastEl = document.getElementById('liveToast');
    const toastBody = document.getElementById('toast-message');

    // Ganti teks toast
    toastBody.innerText = keterangan;

    // Hapus semua class bg-* yang mungkin ada sebelumnya
    toastEl.classList.remove('bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-dark');

    // Tambahkan class warna baru
    toastEl.classList.add(bgClassColor);

    toast.show()
}

function setLoadingButton(btnId, loading = true) {
    const btn = document.getElementById(btnId);
    const spinner = btn.querySelector('.spinner-border');
    const text = btn.querySelector('.btn-text');

    if (loading) {
        spinner.classList.remove('d-none');
        text.innerText = 'Mengecek Jadwal ...';
        btn.disabled = true;
    } else {
        spinner.classList.add('d-none');
        text.innerHTML = '<span class="align-middle d-sm-inline-block">Selanjutnya</span><i class="icon-base bx bx-chevron-right icon-sm me-sm-n2 me-sm-2"></i>';
        btn.disabled = false;
    }
}

function generateTableHeader(){
    return `
            <table id="dataSarpras" class="table table-bordered table-sm">
                <thead>
                    <tr style="background-color: rgba(8, 60, 132, 0.16) !important">
                        <td class="fw-bold" nowrap style="width: 5%; color: rgb(8, 60, 132)" align="center">No</td>
                        <td class="fw-bold" style="width: 80%; color: rgb(8, 60, 132)" align="center">Nama Sarana/Prasarana</td>
                        <td class="fw-bold" nowrap style="width: 10%; color: rgb(8, 60, 132)" align="center">Jumlah</td>
                        <td class="fw-bold" nowrap style="width: 5%; color: rgb(8, 60, 132)" align="center">Aksi</td>
                    </tr>
                </thead>
                <tbody id="tbodySarpras">
                    <tr>
                        <td class="fw-bold fst-italic">A.</td>
                        <td class="fw-bold fst-italic" colspan="3">Ruangan</td>
                    </tr>
    `;
}

function generateTablePeminjaman(){
    let html = generateTableHeader();
    $.each(dataRuangan, function (index, item) {
        html += `
            <tr>
                <td align="center">${index + 1}</td>
                <td>${item.kode_ruangan} - ${item.nama}</td>
                <td align="center">1</td>
                <td align="center"></td>
            </tr>
        `;
    });

    // Bagian B: Peralatan (judul)
    html += `
        <tr>
            <td class="fw-bold fst-italic">B.</td>
            <td class="fw-bold" colspan="3" style="vertical-align: middle"><span style="font-style: italic; line-height: 2">Peralatan</span>
                <span id="btnTambahPeralatan" class="btn btn-sm btn-primary float-end">+ Tambah Peralatan</span>
            </td>
        </tr>
    `;

    // Baris awal peralatan (kosong)
    html += getBarisPeralatan(1);

    html += `
            </tbody>
        </table>
    `;

    $('#tabelPeminjaman').html(html);

    $('#btnTambahPeralatan').click(() => {
        $('#tbodySarpras').append(getBarisPeralatan(nomorPeralatan));
        nomorPeralatan++;
    });

    $(document).on('click', '.btnHapusBaris', function () {
        $(this).closest('tr').remove(); // hapus baris tempat tombol itu berada
        updateNomorBarisPeralatan();   // update ulang nomor
    });
}

function getBarisPeralatan(no) {
    return `
        <tr class="baris-peralatan" id="peralatan-${no}">
            <td align="center">${no}</td>
            <td><input type="text" name="peralatan_nama[]" class="form-control" placeholder="Nama Peralatan"></td>
            <td><input type="number" name="peralatan_jumlah[]" class="form-control" placeholder="Jumlah"></td>
            <td><span class="text-danger cursor-pointer btnHapusBaris"><i class="bx bx-trash"></i></span></td>
        </tr>
    `;
}

function updateNomorBarisPeralatan() {
    $('.baris-peralatan').each(function (i) {
        $(this).find('.nomor-peralatan').text(i + 1);
    });

    nomorPeralatan = $('.baris-peralatan').length + 1;
}
