$(document).ready(function () {
    $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: routeName,
        language: {
            emptyTable: "Data tidak ditemukan",
            zeroRecords: "Tidak ada hasil yang cocok",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan _END_ dari _TOTAL_ data",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'judul', name: 'judul' },
            { data: 'author', name: 'author' },
            { data: 'tanggal_post', name: 'tanggal_post' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ],
        dom:
            //'Bfrtip',
            '<"mb-5 pb-4 border-bottom  d-flex justify-content-between align-items-center"<"head-label text-center"><"dt-action-buttons text-end"B>><"d-flex mb-5 justify-content-between align-items-center row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex mt-5 justify-content-between row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 10,
        lengthMenu: [10, 25, 50, 75, 100],
        buttons: [
            {
                extend: 'collection',
                className: 'btn btn-sm btn-success dropdown-toggle me-2',
                text: '<i class="icon-base bx bx-export me-1"></i>Export',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="icon-base bx bx-printer me-1" ></i>Print',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="icon-base bx bx-file me-1" ></i>Csv',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="icon-base bx bxs-file me-1"></i>Excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="icon-base bx bxs-file-pdf me-1"></i>Pdf',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'copy',
                        text: '<i class="icon-base bx bx-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    }
                ]
            },
            {
                text: '<i class="icon-base bx bx-plus me-1"></i> <span class="d-none d-lg-inline-block">Tambah Pengumuman</span>',
                className: 'create-new btn btn-sm btn-primary'
            }
        ]
    });
    $('div.head-label').html('<span class="card-header p-0"><i class="tf-icons bx bx-book-content"></i>&nbsp;List Pengumuman</span>');
})
