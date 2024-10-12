
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Account Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
                 {{ Auth::getUser()->username }}
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="javascript:void(0);" id="logout" class="dropdown-item">
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

@push('append-scripts')
    <script>
        $('#logout').click(function () {
            return Swal.fire({
                title: "Peringatan",
                text: "Anda Akan Logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Logout"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('logout',['id' => Auth::id()]) }}"
                }
            })
        })
    </script>
@endpush

