$(document).ready(function () {
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

    let calendarEl = document.getElementById('calendar');
    let eventsData = [
        {
            id: '1',
            title: 'Design Review',
            start: '2025-04-29T14:00:00',
            end: '2025-04-29T15:00:00',
            extendedProps: {
                calendar: 'primary',
                type: 'booking'
            }
        },
        {
            id: '2',
            title: 'Team Standup',
            start: '2025-04-29T09:00:00',
            end: '2025-04-29T09:30:00',
            extendedProps: {
                calendar: 'primary',
                type: 'booking'
            }
        },
        {
            id: '3',
            title: 'Client Call',
            start: '2025-04-29T16:00:00',
            end: '2025-04-29T17:00:00',
            extendedProps: {
                calendar: 'success',
                type: 'jadwal'
            }
        },
        {
            id: '4',
            title: 'Lunch Break',
            start: '2025-04-29T12:00:00',
            end: '2025-04-29T13:00:00',
            extendedProps: {
                calendar: 'success',
                type: 'jadwal'
            }
        },
        {
            id: '5',
            title: 'Project Planning',
            start: '2025-04-29T10:30:00',
            end: '2025-04-29T12:00:00',
            extendedProps: {
                calendar: 'success',
                type: 'jadwal'
            }
        },
        {
            id: '6',
            title: 'Project Planning',
            start: '2025-04-30T10:30:00',
            end: '2025-04-30T12:00:00',
            extendedProps: {
                calendar: 'success',
                type: 'jadwal'
            }
        }
    ];

    let calendar = new Calendar(calendarEl, {
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
        dayMaxEvents: 3,
        eventLimitClick: 'popover',
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

            $('#eventModalTitle').text(info.event.title);
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


            return {
                html: `
                   <div>
                        ${arg.event.title}:${timeRange ? ` <span>${timeRange} </span>` : ''}
                   </div>`
            };
        },
        selectable: true,
        select: function(info) {
            // Open a prompt to add an event
            let eventTitle = prompt("Enter event title:");
            if (eventTitle) {
                let newEvent = {
                    title: eventTitle,
                    start: info.startStr,
                    end: info.endStr
                };
                eventsData.push(newEvent);
                calendar.addEvent(newEvent);
            }
        },
    });

    calendar.render();
    I();
    document.querySelectorAll('[data-bs-toggle="sidebar"]').forEach((function(e) {
            e.addEventListener("click", (function() {
                    var t = e.getAttribute("data-target")
                        , n = e.getAttribute("data-overlay")
                        , o = document.querySelectorAll(".app-overlay");
                    document.querySelectorAll(t).forEach((function(e) {
                            e.classList.toggle("show"),
                            null != n && !1 !== n && void 0 !== o && (e.classList.contains("show") ? o[0].classList.add("show") : o[0].classList.remove("show"),
                                o[0].addEventListener("click", (function(t) {
                                        t.currentTarget.classList.remove("show"),
                                            e.classList.remove("show")
                                    }
                                )))
                        }
                    ))
                }
            ))
        }
    ))
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

    loadFilteredEvents()
});

