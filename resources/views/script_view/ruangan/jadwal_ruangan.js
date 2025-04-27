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
        { title: 'Meeting with John', date: '2025-04-28', category: 'meeting' },
        { title: 'Workshop on React', date: '2025-04-30', category: 'workshop' },
        { title: 'Team Building', date: '2025-05-02', category: 'meeting' },
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
        headerToolbar: {
            start: "sidebarToggle, prev,next, title",
            end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
        },
        events: eventsData,
            eventClick: function(info) {
            alert('Event: ' + info.event.title);
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
});

