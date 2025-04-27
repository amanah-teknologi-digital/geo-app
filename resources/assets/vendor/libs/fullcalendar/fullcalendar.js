import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timegridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import interactionPlugin from '@fullcalendar/interaction';

try {
    window.Calendar = Calendar
    window.dayGridPlugin = dayGridPlugin
    window.timegridPlugin = timegridPlugin
    window.listPlugin = listPlugin
    window.bootstrap5Plugin = bootstrap5Plugin
    window.interactionPlugin = interactionPlugin
} catch (e) {}

export { Calendar, dayGridPlugin, timegridPlugin, listPlugin, bootstrap5Plugin, interactionPlugin }
