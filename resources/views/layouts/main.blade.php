<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Fixed Sidebar</title>

    @stack('prepend-styles')
    @include('layouts.dependencies.stayles')
    @stack('append-styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.includes.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                {{-- <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
                <span class="brand-text font-weight-light">Koperasi Al-Izzah</span>
            </a>

            <!-- Sidebar -->
            @include('layouts.includes.sidebar')
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @include('layouts.includes.header')

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    @yield('contents')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.includes.footer')
        <!-- /.sidebar-custom -->
    </div>
    <!-- ./wrapper -->

    @stack('prepend-scripts')
    @include('layouts.dependencies.scripts')
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('assets/dist/js/demo.js') }}"></script>
    @stack('append-scripts')

    @error('failed')
    <script>
        Swal.fire({
            icon: 'error',
            title: '{!! $message !!}'
        })
    </script>
    @enderror
</body>
</html>
