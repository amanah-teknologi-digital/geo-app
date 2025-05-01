let stepper;
let eventsData = [];
let calendar;
let formValidationEL;
let formValidation;
let stepperEl;
let instanceJadwal, instanceJamMulai, instanceJamSelesai;

$(document).ready(function () {
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

    // "Tampilkan Semua" checkbox
    $('#selectAll').on('change', function() {
        let isChecked = $(this).is(':checked');

        $('.input-filter').prop('checked', isChecked);

        loadFilteredEvents();
    });

    // Checkbox individu (jadwal kuliah, jadwal booking)
    $('.input-filter').on('change', function() {
        let allChecked = $('.input-filter').length === $('.input-filter:checked').length;
        $('#selectAll').prop('checked', allChecked);

        loadFilteredEvents();
    });
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
            checkAvaliableJadwal();
            //stepper.to(3);
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
            ruangan: {
                required: true
            },
            tanggal_booking: {
                required: true
            },
            jam_mulai: {
                required: true,
                time24:true
            },
            jam_selesai: {
                required: true,
                time24:true
            }
        },
        messages: {
            status_peminjam: {
                required: "Status peminjam wajib diisi."
            },
            ruangan: {
                required: "Ruangan wajib diisi."
            },
            tanggal_booking: {
                required: "Tanggal booking wajib diisi."
            },
            jam_mulai: {
                required: "Jam mulai wajib diisi."
            },
            jam_selesai: {
                required: "Jam selesai wajib diisi."
            }
        },
        ignore: ":hidden",
        errorPlacement: function (error, element) {
            if (element.attr("name") === "jam_mulai") {
                error.appendTo("#error-jammulai");
            }else if (element.attr("name") === "jam_selesai") {
                error.appendTo("#error-jamselesai");
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
        height: '100%',
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
            const title = info.event.title + (type === 'booking' ? ' (booking)' : '');

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
                        ${arg.event.title}${typeLabel}:${timeRange ? ` <span>${timeRange} </span>` : ''}
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

    $.ajax({
        url: urlGetData,  // Ganti dengan URL API yang sesuai
        method: 'GET',
        dataType: 'json',
        data:{
            'id_ruangan': idRuangan
        },
        success: function(response) {
            eventsData = [
                ...response.jadwal,  // Data jadwal
                ...response.booking  // Data booking
            ];

            loadFilteredEvents();
        },
        error: function(xhr, status, error) {
            eventsData = [];
            loadFilteredEvents();
        }
    });
}

function checkAvaliableJadwal(){
    let idRuangan = $('#ruangan').val();
    let tanggalBooking = $('#tanggal_booking').val();
    let jamMulai = $('#jam_mulai').val();
    let jamSelesai = $('#jam_selesai').val();

    $.ajax({
        url: urlCheckJadwalRuangan,  // Ganti dengan URL API yang sesuai
        method: 'GET',
        dataType: 'json',
        data:{
            'id_ruangan': idRuangan,
            'tanggal_booking': tanggalBooking,
            'jam_mulai': jamMulai,
            'jam_selesai': jamSelesai
        },
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            instanceJadwal.clear();
            instanceJamMulai.clear();
            instanceJamSelesai.clear();
        }
    });
}

function loadFilteredEvents() {
    let selectedTypes = [];

    $('.input-filter:checked').each(function() {
        selectedTypes.push($(this).data('value'));
    });

    let filteredEvents = [];

    if (selectedTypes.length > 0) {
        // Ada filter aktif, tampilkan event yang cocok
        filteredEvents = eventsData.filter(function(event) {
            return selectedTypes.includes(event.extendedProps?.type);
        });
    }
    // else (kalau kosong, biarkan filteredEvents kosong)

    calendar.removeAllEvents();
    calendar.addEventSource(filteredEvents);
}

function formatDate(date) {
    let day = String(date.getDate()).padStart(2, '0');
    let month = String(date.getMonth() + 1).padStart(2, '0');  // Bulan dimulai dari 0
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}
