<!-- BEGIN: Vendor JS-->

@vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/popper/popper.js',
  'resources/assets/vendor/js/bootstrap.js',
  'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
  'resources/assets/vendor/js/menu.js',
  'resources/assets/vendor/libs/jquery-validation/jquery-validation.js',
  'resources/assets/vendor/libs/datatable/datatable.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/tinymce/tinymce.js',
  'resources/assets/vendor/libs/fullcalendar/fullcalendar.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/bs-stepper/bs-stepper.js',
])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
<script>
    let routeGetDataNotifikasi = "{{ route('dashboard.getdatanotifikasi') }}";
</script>
@vite(['resources/views/script_view/notifikasi/script.js'])
