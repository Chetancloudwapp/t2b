
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>T2b</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}} ">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/css/adminlte.min2167.css?v=3.2.0')}}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css')}}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.min.css')}}">

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center"> <img class="animation__shake" src="{{ asset('admin/images/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60"> </div>
        
        <!-- header code here -->
        @include('admin::admin.layout.header')

        <!-- sidebar code starts here -->
        @include('admin::admin.layout.sidebar')

       
        <!-- middlecontent starts here -->
        @yield('content')

        <!-- footer code start here -->
        @include('admin::admin.layout.footer')
        <aside class="control-sidebar control-sidebar-dark"> </aside>
    </div>

    <!-- important scripts -->
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    
    <!-- datatable scripts -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    
    <!-- Sweet-alert-script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Summernote-script -->
    <script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>

    <script src="{{ asset('admin/js/adminlte2167.js?v=3.2.0')}}"></script>
    <script src="{{ asset('admin/js/demo.js')}}"></script>
    <script src="{{ asset('admin/js/pages/dashboard.js')}}"></script>
    <!-- Custom Js File -->
    <script src="{{ asset('admin/js/custom.js')}}"></script>
    
    <script>
        const BaseadminUrl="{{ url('admin') }}"
    $(function () {
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
    $('#categories').DataTable();
    });
    </script>
    <script>
        $('.summernote').summernote({
            tabsize: 2,
            height: 250
        });
    </script>
    </body>
    </html>