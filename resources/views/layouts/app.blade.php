<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema - @yield('title_page')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{assets("plugins/fontawesome-free/css/all.min.css")}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{assets("dist/css/adminlte.min.css")}}">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{assets("plugins/datatables-bs4/css/dataTables.bootstrap4.min.css")}}">
  <link rel="stylesheet" href="{{assets("plugins/datatables-responsive/css/responsive.bootstrap4.min.css")}}">
  <link rel="stylesheet" href="{{assets("plugins/datatables-buttons/css/buttons.bootstrap4.min.css")}}">
  
  {{--aweetalert2---}}
  <link rel="stylesheet" href="{{assets("sweetalert2/dist/sweetalert2.css")}}">
  <link rel="stylesheet" href="{{assets("sweetalert2/dist/sweetalert2.min.css")}}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
   @include('../resources/views/components/nav.blade.php')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('../resources/views/components/aside.blade.php')

  <!-- Content Wrapper. Contains page content -->
  @include('../resources/views/components/wrapper.blade.php')
  <!-- /.content-wrapper -->

 
  <!-- /.control-sidebar -->
  @include('../resources/views/components/footer.blade.php')
  <!-- Main Footer -->
 
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{assets("plugins/jquery/jquery.min.js")}}"></script>
<!-- Bootstrap 4 -->
<script src="{{assets("plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{assets("dist/js/adminlte.min.js")}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{assets("plugins/datatables/jquery.dataTables.min.js")}}"></script>
<script src="{{assets("plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")}}"></script>
<script src="{{assets("plugins/datatables-responsive/js/dataTables.responsive.min.js")}}"></script>
<script src="{{assets("plugins/datatables-responsive/js/responsive.bootstrap4.min.js")}}"></script>
<script src="{{assets("plugins/datatables-buttons/js/dataTables.buttons.min.js")}}"></script>
<script src="{{assets("plugins/datatables-buttons/js/buttons.bootstrap4.min.js")}}"></script>
<script src="{{assets("plugins/jszip/jszip.min.js")}}"></script>
<script src="{{assets("plugins/pdfmake/pdfmake.min.js")}}"></script>
<script src="{{assets("plugins/pdfmake/vfs_fonts.js")}}"></script>
<script src="{{assets("plugins/datatables-buttons/js/buttons.html5.min.js")}}"></script>
<script src="{{assets("plugins/datatables-buttons/js/buttons.print.min.js")}}"></script>
<script src="{{assets("plugins/datatables-buttons/js/buttons.colVis.min.js")}}"></script>

{{--SWEET ALERT 2---}}
<script src="{{assets("sweetalert2/dist/sweetalert2.all.js")}}"></script>
<script src="{{assets("sweetalert2/dist/sweetalert2.all.min.js")}}"></script>
<script src="{{assets("sweetalert2/dist/sweetalert2.js")}}"></script>
<script src="{{assets("sweetalert2/dist/sweetalert2.min.js")}}"></script>
@yield('js')
</body>
</html>
