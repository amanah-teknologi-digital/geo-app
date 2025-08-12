let eventsData = [];
let calendarEl;
let calendar;
let instanceJadwalUpdate, instanceJamMulaiUpdate, instanceJamSelesaiUpdate;

$(document).ready(function () {
    instanceJadwalUpdate = inisiasiTanggal("#tgl_jadwal_update");
    instanceJamMulaiUpdate = inisiasiJamMulai('jam_mulai_update', 'jam_selesai_update');
    instanceJamSelesaiUpdate = inisiasiJamSelesai('jam_mulai_update', 'jam_selesai_update');

    calendarEl = document.getElementById('calendar');
    inisiasiCalendar();
    I();
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

    $('#addEventSidebar').on('shown.bs.offcanvas', function () {
        // Reset form atau elemen lainnya
        resetInputTambah()
    });

    getDataJadwal();
});

function inisiasiCalendar() {
    calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timegridPlugin, listPlugin],
        initialView: 'dayGridMonth',
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
            resetInputUpdate();
            setDataUpdateJadwal(info);

            var offcanvas = new bootstrap.Offcanvas(document.getElementById('addEventSidebarUpdate'));
            offcanvas.show();
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

function setDataUpdateJadwal(data){
    $('#keterangan_update').val(data.event.extendedProps.keterangan);
    $('#hari_update').val(data.event.extendedProps.day_of_week);

    let tgl_mulai = new Date(data.event.extendedProps.tgl_mulai);
    let tgl_selesai = new Date(data.event.extendedProps.tgl_selesai);
    let type = data.event.extendedProps.type;

    instanceJadwalUpdate.setDate([tgl_mulai, tgl_selesai], true)
    instanceJamMulaiUpdate.setDate(data.event.extendedProps.jam_mulai)
    instanceJamSelesaiUpdate.setDate(data.event.extendedProps.jam_selesai)

    instanceJadwalUpdate.destroy();
    instanceJamMulaiUpdate.destroy();
    instanceJamSelesaiUpdate.destroy();
    instanceJadwalUpdate = null;
    instanceJamMulaiUpdate = null;
    instanceJamSelesaiUpdate = null;
    $('#keterangan_update').prop('disabled', true);
    $('#hari_update').prop('disabled', true);
    $('#tgl_jadwal_update').prop('disabled', true);
    $('#jam_mulai_update').prop('disabled', true);
    $('#jam_selesai_update').prop('disabled', true);
}

function resetInputUpdate(){
    if (!instanceJadwalUpdate) {
        instanceJadwalUpdate = inisiasiTanggal("#tgl_jadwal_update");
    }
    if (!instanceJamMulaiUpdate) {
        instanceJamMulaiUpdate = inisiasiJamMulai('jam_mulai_update', 'jam_selesai_update');
    }
    if (!instanceJamSelesaiUpdate) {
        instanceJamSelesaiUpdate = inisiasiJamSelesai('jam_mulai_update', 'jam_selesai_update');
    }

    $('#keterangan_update').val('');
    $('#hari_update').val('');
    instanceJadwalUpdate.clear();
    instanceJamMulaiUpdate.clear();
    instanceJamSelesaiUpdate.clear();
}

function inisiasiTanggal(dom){
    return flatpickr(dom, {
        mode: "range",
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
    $.ajax({
        url: urlGetData,  // Ganti dengan URL API yang sesuai
        method: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
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

