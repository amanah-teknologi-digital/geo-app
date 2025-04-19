import Quill from 'quill';
import 'quill/dist/quill.snow.css';
$(document).ready(function () {
    const quill = new Quill('#editor_pengumuman', {
        theme: 'snow',
        readOnly: true, // Mode hanya baca
        modules: {
            toolbar: false // Hilangkan toolbar
        }
    });
})
