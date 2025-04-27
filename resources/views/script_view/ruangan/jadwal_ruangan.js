$(document).ready(function () {
    function I() {
        var e = document.querySelector(".fc-sidebarToggle-button");
        for (e.classList.remove("fc-button-primary"),
                 e.classList.add("d-lg-none", "d-inline-block", "ps-0", "btn-toggle-sidebar"); e.firstChild; )
            e.firstChild.remove();
            e.setAttribute("data-bs-toggle", "sidebar"),
            e.setAttribute("data-overlay", ""),
            e.setAttribute("data-target", "#app-calendar-sidebar"),
            e.insertAdjacentHTML("beforeend", '<i class="icon-base bx bx-menu icon-lg text-heading"></i>')
    }

    let t = document.querySelector(".app-calendar-sidebar");
    var x = document.getElementById("addEventSidebar");
    let n = document.querySelector(".app-overlay");
    var T = document.querySelector(".btn-toggle-sidebar");

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
                text: "Sidebar"
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
    x.addEventListener("hidden.bs.offcanvas", function() {
        F()
    });
    T.addEventListener("click", e => {
        console.log('oke')
        t.classList.remove("show");
        n.classList.remove("show");
    });
});
