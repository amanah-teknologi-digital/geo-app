$(document).ready(function () {
    let calendarEl = document.getElementById('calendar');
    let eventsData = [
        { title: 'Meeting with John', date: '2025-04-28', category: 'meeting' },
        { title: 'Workshop on React', date: '2025-04-30', category: 'workshop' },
        { title: 'Team Building', date: '2025-05-02', category: 'meeting' },
    ];

    let calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timegridPlugin, listPlugin],
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
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
})
