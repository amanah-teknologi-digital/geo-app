$(document).ready(function () {
    const stepper = new Stepper(document.querySelector('#wizard'), {
        linear: false,
        animation: true
    });

    $('#btn-next-1').click(() => stepper.next());
    $('#btn-next-2').click(() => stepper.next());
    $('#btn-prev-2').click(() => stepper.previous());
    $('#btn-prev-3').click(() => stepper.previous());
});
