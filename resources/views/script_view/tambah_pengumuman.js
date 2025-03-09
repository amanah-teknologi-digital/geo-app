import Quill from 'quill';
import 'quill/dist/quill.snow.css';
$(document).ready(function () {
    var quill = new Quill('#editor_pengumuman', {
        theme: 'snow' // Bisa 'snow' atau 'bubble'
    });
})
