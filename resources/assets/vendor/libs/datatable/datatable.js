import DataTable from 'datatables.net-bs5/js/dataTables.bootstrap5.min';
import 'datatables.net-responsive-bs5/js/responsive.bootstrap5.min';
import 'datatables.net-buttons-bs5/js/buttons.bootstrap5.min';
import 'datatables.net-buttons/js/buttons.colVis.min';
import 'datatables.net-buttons/js/buttons.html5.min';
import 'datatables.net-buttons/js/buttons.print.min';

try {
    window.DataTable = DataTable;
} catch (e) {}

export { DataTable };
