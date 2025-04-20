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

            setDataNotifikasi(dataNotifSurat)
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
}

function setDataNotifikasi(data){
    let isNotif = data['isNotif'];
    let isNotifSurat = data['isNotifSurat'];
    let jmlNotif = data['jmlNotif'];
    let jmlNotifAjukan = data['jmlNotifAjukan'];
    let jmlNotifVerifikasi = data['jmlNotifVerifikasi'];
    let jmlNotifRevisi = data['jmlNotifRevisi'];

    if (isNotif){
        $('#tanda_notif').show();
        $('#icon_notifikasi').addClass('bounce');
    }

    if (jmlNotif.length > 0){
        $('#pesan_notifikasi').html(jmlNotif.length + ' Baru');
    }

    if (isNotifSurat){
        $('#data_notif_surat').show();
    }

    if (jmlNotifAjukan > 0){
        $('#notif_surat_ajukan').show();
        $('#jml_surat_ajukan').html(jmlNotifAjukan);
    }

    if (jmlNotifVerifikasi > 0){
        $('#notif_surat_verifikasi').show();
        $('#jml_surat_verifikasi').html(jmlNotifVerifikasi);
    }

    if (jmlNotifRevisi > 0){
        $('#notif_surat_revisi').show();
        $('#jml_surat_revisi').html(jmlNotifRevisi);
    }
}
