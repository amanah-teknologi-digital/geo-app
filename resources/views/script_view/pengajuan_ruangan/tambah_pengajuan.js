let stepper;
$(document).ready(function () {
    const stepperEl = document.querySelector('.bs-stepper');
    if (stepperEl) {
        stepper = new Stepper(stepperEl, {
            linear: false,
            animation: true
        });
    } else {
        console.error('Stepper element not found!');
    }

    const form = $('#wizard-validation');

    form.validate({
        rules: {
            status_peminjam: {
                required: true
            }
        },
        messages: {
            status_peminjam: {
                required: "Status peminjam wajib diisi."
            }
        }
    });

    // Tangkap saat user klik step header
    stepperEl.addEventListener('show.bs-stepper', function (event) {
        const fromStep = event.detail.from;
        const toStep = event.detail.indexStep;

        if (toStep > fromStep) {
            // hanya validasi saat maju
            const valid = form.valid();

            if (!valid) {
                event.preventDefault(); // batal pindah step
            }
        }
    });

    // Tombol custom (jika dipakai)
    $('#btn-next-1').click(() => {
        if (form.valid()) stepper.to(2);
    });

    $('#btn-prev-1').click(() => stepper.to(1));

    $('#btn-next-2').click(() => {
        if (form.valid()) stepper.to(3);
    });

    $('#btn-prev-2').click(() => stepper.to(2));
});
