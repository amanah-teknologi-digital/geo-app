$(document).ready(function () {
    $("#datatable").DataTable({
        data:[
            {
                "id": 1,
                "full_name": "John Doe",
                "email": "john@example.com",
                "start_date": "2023-01-10",
                "salary": 50000,
                "status": "Active"
            },
            {
                "id": 2,
                "full_name": "Jane Smith",
                "email": "jane@example.com",
                "start_date": "2022-03-15",
                "salary": 60000,
                "status": "Inactive"
            },
            {
                "id": 3,
                "full_name": "Alice Brown",
                "email": "alice@example.com",
                "start_date": "2021-07-23",
                "salary": 70000,
                "status": "Active"
            },
            {
                "id": 4,
                "full_name": "Bob Williams",
                "email": "bob@example.com",
                "start_date": "2020-12-01",
                "salary": 55000,
                "status": "Active"
            },
            {
                "id": 5,
                "full_name": "Charlie Davis",
                "email": "charlie@example.com",
                "start_date": "2019-05-30",
                "salary": 65000,
                "status": "Inactive"
            }
        ],
        columns: [
            { data: 'id' },
            { data: 'full_name' },
            { data: 'email' },
            { data: 'start_date' },
            { data: 'salary' },
            { data: 'status' }
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
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="icon-base bx bx-file me-1" ></i>Csv',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="icon-base bx bxs-file me-1"></i>Excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="icon-base bx bxs-file-pdf me-1"></i>Pdf',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                    },
                    {
                        extend: 'copy',
                        text: '<i class="icon-base bx bx-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
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
