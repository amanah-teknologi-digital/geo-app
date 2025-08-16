let formValidation;
let toast;

$(document).ready(function () {
    $('#pemeriksa_awal').select2({
        placeholder: 'Cari user...',
        width: '100%',
        ajax: {
            url: urlGetUser,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(user => ({
                        id: user.id,
                        text: user.name
                    }))
                };
            },
            cache: true
        }
    });

    const toastLive = document.getElementById('liveToast')
    toast = new bootstrap.Toast(toastLive)
});
