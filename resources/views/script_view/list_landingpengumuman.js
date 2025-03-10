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
            { data: 'judul', name: 'judul', className: 'all', searchable: true },
            { data: 'pembuat', name: 'created_at', className: 'all', searchable: true },
            { data: 'posting', name: 'is_posting', className: 'all text-center', searchable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'all text-center text-nowrap' }
        ],
        order: [[2, 'desc']],
        displayLength: 5,
        lengthMenu: [5, 10, 20, 30, 50]
    });
    $('div.head-label').html('<span class="card-header p-0"><i class="tf-icons bx bx-book-content"></i>&nbsp;List Pengumuman</span>');
})
