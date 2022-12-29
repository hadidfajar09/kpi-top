
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>KPI - @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="icon" href="{{ asset($setting->path_logo) }}" type="image/*">
   <!-- DataTables -->
   <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
   <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
   <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- overlayScrollbars -->
  

  <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('backend_pemda/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
  @stack('css')
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="{{ asset('img/logo-2022-12-10145132.png') }}" alt="SIMOJI" height="60" width="60">
  </div>

  <!-- Navbar -->
  @include('backend.partial.header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('backend.partial.sidebar')

  <!-- Content Wrapper. Contains page content -->
  @yield('content')
  <!-- /.content-wrapper -->

  @include('backend.partial.footer')

  <!-- Main Footer -->
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('backend_pemda/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend_pemda/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('backend_pemda/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('backend_pemda/dist/js/adminlte.js') }}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{ asset('backend_pemda/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('backend_pemda/plugins/chart.js/Chart.min.js') }}"></script>



<!-- DataTables  & Plugins -->
<script src="{{ asset('backend_pemda/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


{{-- VALIDATOR --}}
{{-- <script src="{{ asset('js/validator.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>

  
<!-- AdminLTE for demo purposes -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('backend_pemda/dist/js/demo.js') }}"></script>

<script>
  function preview(selector,temporaryFile,width = 200){
    $(selector).empty();
    $(selector).append(`<img src="${window.URL.createObjectURL(temporaryFile)}" width="${width}">`);
  }

  $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
</script>

@stack('scripts')
</body>

@include('sweetalert::alert')

</html>
