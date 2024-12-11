<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koperasi Al-Izzah | {{ ucfirst($menu) }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">

    @stack('prepend-styles')
    @include('layouts.dependencies.stayles')
    @stack('append-styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed {{ $menu == 'kasir' ? 'sidebar-collapse' : '' }}">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.includes.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard.main') }}" class="brand-link">
                <img src="{{ asset('favicon_io/android-chrome-512x512.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
