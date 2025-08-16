import ApexCharts from 'apexcharts-clevision';
let chart;
$(document).ready(function () {
    initiateChart();
    getDataRuang();

    $('#tahun').on('change', function () {
        getDataRuang();
    });
});

function initiateChart(){
    let timestamps = []
    let jumlahPengajuan = [];
    let jumlahDisetujui = [];
    let jumlahDitolak = [];

    let options = {
        chart: {
            type: 'area',
            stacked: true,
            height: 450,
            zoom: {
                enabled: true,
                type: 'x',
                autoScaleYaxis: true
            },
            toolbar: {
                autoSelected: 'pan',
                tools: {
                    pan: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    reset: true
                }
            }
        },
        title: {
            text: 'Statistik Pengajuan ' + istilahRuangan + ' - 7 Hari Terakhir',
            align: 'left',
            style: {
                fontSize: '15px',
                fontWeight: 'bold'
            }
        },
        subtitle: {
            text: 'Jumlah Pengajuan, Disetujui, dan Ditolak',
            align: 'left'
        },
        xaxis: {
            type: 'datetime',
            categories: timestamps,
            min: timestamps[timestamps.length-8],
            max: timestamps[timestamps.length-1],
            labels: {
                rotate: -45,
                format: 'dd MMM'
            }
        },
        yaxis: {
            min: 0,
            labels: {
                formatter: function (val) {
                    return val.toFixed(0); // menghilangkan .0
                }
            }
        },
        series: [
            {
                name: 'Jumlah Pengajuan',
                data: jumlahPengajuan
            },
            {
                name: 'Disetujui',
                data: jumlahDisetujui
            },
            {
                name: 'Ditolak',
                data: jumlahDitolak
            }
        ],
        stroke: {
            curve: 'smooth',
            width: 2
        },
        tooltip: {
            shared: true,
            x: {
                format: 'dd MMM yyyy'
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center'
        }
    };

    chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
}

function getDataRuang(){
    let tahun = $('#tahun').val();

    setLoading();

    $.ajax({
        url: routeGetDataRuang,
        type: 'GET',
        data: { tahun: tahun },
        dataType: 'json',
        success: function(response) {
            let dataRuangan = response['dataRuangan'];
            let dataStatistikRuangan = response['dataStatistikRuangan'];

            setDataStatus(dataRuangan);
            generateChart(dataStatistikRuangan);
        },
        error: function(xhr, status, error) {
            resetChart();
        }
    });
}

function setLoading(){
    $('#total_pengajuan').html('<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>')
    $('#total_disetujui').html('<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>')
    $('#total_onproses').html('<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>')
    $('#total_ditolak').html('<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>')

    chart.updateOptions({
        noData: {
            text: 'Loading ...',
            align: 'center',
            verticalAlign: 'middle',
            offsetX: 0,
            offsetY: 0,
            style: {
                color: '#999',
                fontSize: '16px'
            }
        }
    });
}

function setDataStatus(data){
    $('#total_pengajuan').html(data.total_pengajuan);
    $('#total_disetujui').html(data.disetujui);
    $('#total_onproses').html(data.on_proses);
    $('#total_ditolak').html(data.ditolak);
}

function generateChart(data){
    let timestamps = data['listTanggal'];
    let jumlahPengajuan = data['listPengajuan'];
    let jumlahDisetujui = data['listDisetujui'];
    let jumlahDitolak   = data['listDitolak'];

    chart.updateOptions({
        xaxis: {
            categories: timestamps
        },
        noData: {
            text: 'Tidak ada data!',
            align: 'center',
            verticalAlign: 'middle',
            offsetX: 0,
            offsetY: 0,
            style: {
                color: '#999',
                fontSize: '16px'
            }
        },
    });

    chart.updateSeries([
        {
            name: 'Jumlah Pengajuan',
            data: jumlahPengajuan
        },
        {
            name: 'Disetujui',
            data: jumlahDisetujui
        },
        {
            name: 'Ditolak',
            data: jumlahDitolak
        }
    ]);
}

function resetChart(){
    chart.updateOptions({
        xaxis: {
            categories: []
        },
        noData: {
            text: 'Error saat mendapatkan data!',
            align: 'center',
            verticalAlign: 'middle',
            offsetX: 0,
            offsetY: 0,
            style: {
                color: '#c70a0a',
                fontSize: '16px'
            }
        },
    });

    chart.updateSeries([
        {
            name: 'Jumlah Pengajuan',
            data: []
        },
        {
            name: 'Disetujui',
            data: []
        },
        {
            name: 'Ditolak',
            data: []
        }
    ]);
}
