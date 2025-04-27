import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timegridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

try {
    window.Calendar = Calendar
    window.dayGridPlugin = dayGridPlugin
    window.timegridPlugin = timegridPlugin
    window.listPlugin = listPlugin
    window.interactionPlugin = interactionPlugin
} catch (e) {}

export { Calendar, dayGridPlugin, timegridPlugin, listPlugin, interactionPlugin }
