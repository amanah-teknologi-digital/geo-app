let stepper;
$(document).ready(function () {
    const stepperEl = document.querySelector('.bs-stepper');
    if (stepperEl) {
        stepper = new Stepper(stepperEl, {
            linear: false,
            animation: true
        });

        $('#btn-next-1').click(() => stepper.to(2));
        $('#btn-prev-1').click(() => stepper.to(1));
        $('#btn-prev-2').click(() => stepper.to(2));
        $('#btn-next-2').click(() => stepper.to(3));
    } else {
        console.error('Stepper element not found!');
    }
});
