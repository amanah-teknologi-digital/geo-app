$(document).ready(function () {
    $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: routeName,
        responsive: true,
        scrollX: true,
        language: {
            emptyTable: "Data tidak ditemukan",
            zeroRecords: "Tidak ada hasil yang cocok",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan _END_ dari _TOTAL_ data",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'all text-center' },
            { data: 'jenissurat', name: 'jenissurat', className: 'all', searchable: true },
            { data: 'updater', name: 'updater', className: 'all', searchable: false },
            { data: 'status', name: 'status', className: 'all text-center', searchable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'all text-center text-nowrap' }
        ],
        dom:
            //'Bfrtip',
            '<"mb-5 pb-4 border-bottom d-flex justify-content-between align-items-center"<"head-label text-center"><"dt-action-buttons text-end"B>><"d-flex mb-5 justify-content-between align-items-center row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex mt-5 justify-content-between row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 10,
        lengthMenu: [5, 10, 20, 30, 50],
        buttons: [
            {
                extend: 'collection',
                className: 'btn btn-sm btn-outline-success dropdown-toggle me-2',
                text: '<i class="icon-base bx bx-export me-1"></i><span class="d-none d-lg-inline-block">Export</span>',
                buttons: [
                    {
                        extend: 'print',
                        title: title,
                        text: '<i class="icon-base bx bx-printer me-1" ></i>Print',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'csv',
                        title: title,
                        text: '<i class="icon-base bx bx-file me-1" ></i>Csv',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'excel',
                        title: title,
                        text: '<i class="icon-base bx bxs-file me-1"></i>Excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'pdf',
                        title: title,
                        text: '<i class="icon-base bx bxs-file-pdf me-1"></i>Pdf',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] },
                        customize: function(doc) {
                            doc.content[1].layout = {
                                hLineWidth: function (i, node) {
                                    return 0.5; // Ketebalan garis horizontal
                                },
                                vLineWidth: function (i, node) {
                                    return 0.5; // Ketebalan garis vertikal
                                },
                                hLineColor: function (i, node) {
                                    return '#000000'; // Warna garis horizontal
                                },
                                vLineColor: function (i, node) {
                                    return '#000000'; // Warna garis vertikal
                                }
                            };

                            doc.content[1].table.body[0].forEach(function(h) {
                                             h.fillColor = '#2D4154';
                                             h.color = 'white';
                            });

                            doc.content[1].table.body.forEach(function(row, i) {
                                row[0].alignment = 'center'; // Kolom No
                                row[3].alignment = 'center'; // Kolom posting
                            });

                            doc.content[1].margin = [ 100, 0, 100, 0 ]

                            //doc.watermark = {text: 'TEKNIK GEOFISIKA ITS', color: 'grey', opacity: 0.1};
                            doc.pageMargins = [10,10,10,10];
                            //doc.defaultStyle.fontSize = 7;
                            //doc.styles.tableHeader.fontSize = 7;
                            //doc.styles.title.fontSize = 9;

                        }
                    },
                    {
                        extend: 'copy',
                        title: title,
                        text: '<i class="icon-base bx bx-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    }
                ]
            },
            {
                text: '<i class="icon-base bx bx-plus me-1"></i> <span class="d-none d-lg-inline-block">Tambah Jenis Surat</span>',
                className: 'create-new btn btn-sm btn-primary',
                action: function (e, dt, node, config) {
                    window.location.href = routeTambah;
                }
            }
        ]
    });
    $('div.head-label').html('<span class="card-header p-0"><i class="tf-icons bx bx-book-content"></i>&nbsp;List Jenis Surat</span>');

    $('#modal-hapus').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id'); // Ambil nilai data-id
        $('#id_hapus').val(dataId); // Masukkan ke modal
    });

    $('#modal-nonaktif').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id'); // Ambil nilai data-id
        $('#id_nonaktif').val(dataId); // Masukkan ke modal
    });

    $('#modal-aktif').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Ambil tombol yang diklik
        var dataId = button.data('id'); // Ambil nilai data-id
        $('#id_aktif').val(dataId); // Masukkan ke modal
    });
})
