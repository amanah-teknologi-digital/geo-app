$(document).ready(function () {
    getDataNotifikasi();
});

function getDataNotifikasi(){
    resetDataNotifikasi()
    showLoader()

    $.ajax({
        url: routeGetDataNotifikasi,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let dataNotifSurat = response['dataNotifSurat'];
            let dataNotifRuangan = response['dataNotifRuangan'];

            setDataNotifikasi(dataNotifSurat, dataNotifRuangan)
            setTimeout(function() {
                hideLoader()
            }, 1000);

        },
        error: function(xhr, status, error) {
            resetDataNotifikasi()
            $('#pesan_notifikasi').html('get data error!');
            setTimeout(function() {
                hideLoader()
            }, 1000);
        }
    });
}

function showLoader(){
    $('#loader_notifikasi').show();
    $('#kontent_notifikasi').hide();
}

function hideLoader(){
    $('#loader_notifikasi').hide();
    $('#kontent_notifikasi').show();
}

function resetDataNotifikasi(){
    $('#loader_notifikasi').hide();
    $('#kontent_notifikasi').show();
    $('#tanda_notif').hide();
    $('#icon_notifikasi').removeClass('bounce');
    $('#pesan_notifikasi').html('tidak ada notifikasi');
    $('#data_notif_surat').hide();
    $('#notif_surat_ajukan').hide();
    $('#notif_surat_verifikasi').hide();
    $('#notif_surat_revisi').hide();
    $('#jml_surat_ajukan').html('0');
    $('#jml_surat_verifikasi').html('0');
    $('#jml_surat_revisi').html('0');
    $('#data_notif_ruangan').hide();
    $('#notif_ruangan_ajukan').hide();
    $('#notif_ruangan_verifikasi').hide();
    $('#notif_ruangan_revisi').hide();
    $('#jml_ruangan_ajukan').html('0');
    $('#jml_ruangan_verifikasi').html('0');
    $('#jml_ruangan_revisi').html('0');
}

function setDataNotifikasi(dataSurat, dataRuangan){
    let isNotif = dataSurat['isNotif'];
    let isNotifSurat = dataSurat['isNotifSurat'];
    let jmlNotifSurat = dataSurat['jmlNotif'];
    let jmlNotifAjukan = dataSurat['jmlNotifAjukan'];
    let jmlNotifVerifikasi = dataSurat['jmlNotifVerifikasi'];
    let jmlNotifRevisi = dataSurat['jmlNotifRevisi'];

    if (!isNotif){
        isNotif = dataRuangan['isNotif'];
    }
    let isNotifRuangan = dataRuangan['isNotifRuangan'];
    let jmlNotifRuangan = dataRuangan['jmlNotif'];
    let jmlNotifRuanganAjukan = dataRuangan['jmlNotifAjukan'];
    let jmlNotifRuanganVerifikasi = dataRuangan['jmlNotifVerifikasi'];

    let jmlNotif = parseInt(jmlNotifSurat) + parseInt(jmlNotifRuangan)

    if (isNotif){
        $('#tanda_notif').show();
        $('#icon_notifikasi').addClass('bounce');
    }

    if (jmlNotif > 0){
        $('#pesan_notifikasi').html(jmlNotif + ' Baru');
    }

    if (isNotifSurat){
        $('#data_notif_surat').show();
    }

    if (isNotifRuangan){
        $('#data_notif_ruangan').show();
    }

    if (jmlNotifAjukan > 0){
        $('#notif_surat_ajukan').show();
        $('#jml_surat_ajukan').html(jmlNotifAjukan);
    }

    if (jmlNotifRuanganAjukan > 0){
        $('#notif_ruangan_ajukan').show();
        $('#jml_ruangan_ajukan').html(jmlNotifRuanganAjukan);
    }

    if (jmlNotifVerifikasi > 0){
        $('#notif_surat_verifikasi').show();
        $('#jml_surat_verifikasi').html(jmlNotifVerifikasi);
    }

    if (jmlNotifRuanganVerifikasi > 0){
        $('#notif_ruangan_verifikasi').show();
        $('#jml_ruangan_verifikasi').html(jmlNotifRuanganVerifikasi);
    }

    if (jmlNotifRevisi > 0){
        $('#notif_surat_revisi').show();
        $('#jml_surat_revisi').html(jmlNotifRevisi);
    }
}
