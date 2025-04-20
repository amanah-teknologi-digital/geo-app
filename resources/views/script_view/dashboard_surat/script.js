import ApexCharts from 'apexcharts-clevision';
$(document).ready(function () {
    getDataSurat();

    $('#tahun').on('change', function () {
        getDataSurat();
    });
});

function getDataSurat(){
    let tahun = $('#tahun').val();

    setLoading();

    $.ajax({
        url: routeGetDataSurat,
        type: 'GET',
        data: { tahun: tahun },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            let dataPersuratan = response['dataPersuratan'];
            setDataStatus(dataPersuratan);
            generateChart(response);
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
}

function setDataStatus(data){
    $('#total_pengajuan').html(data.total_pengajuan);
    $('#total_disetujui').html(data.disetujui);
    $('#total_onproses').html(data.on_proses);
    $('#total_ditolak').html(data.ditolak);
}

function generateChart(data){
    // Generate 100 tanggal (hari ke-1 sampai ke-100)
    const days = Array.from({ length: 100 }, (_, i) => {
        const d = new Date();
        d.setDate(d.getDate() - (99 - i));
        return d.toISOString().split('T')[0];
    });

    // Konversi ke timestamp (UTC)
    const timestamps = days.map(date => new Date(date).getTime());

    // Dummy data untuk simulasi
    const jumlahPengajuan = Array.from({ length: 100 }, () => Math.floor(Math.random() * 41) + 20); // 20–60
    const jumlahDisetujui = Array.from({ length: 100 }, () => Math.floor(Math.random() * 31) + 10); // 10–40
    const jumlahDitolak   = Array.from({ length: 100 }, () => Math.floor(Math.random() * 10) + 1);   // 1–10

    const options = {
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
            text: 'Statistik Pengajuan - 7 Hari Terakhir',
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
            min: timestamps[93],
            max: timestamps[99],
            labels: {
                rotate: -45,
                format: 'dd MMM'
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

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
}

function resetChart(){
    const options = {
        chart: {
            type: 'area',
            stacked: true,
            height: 350,
            zoom: {
                enabled: true,
                type: 'x',
                autoScaleYaxis: true
            },
            toolbar: {
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
            text: 'Statistik Pengajuan - 7 Hari Terakhir',
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
            labels: {
                rotate: -45,
                format: 'dd MMM'
            }
        },
        series: [],
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

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
}
