<div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        @php
            $role = Auth::getUser()->role;
        @endphp
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if ($role == 'superadmin' || $role == 'admin' || $role == 'yayasan')
            <li class="nav-header">RINGKASAN</li>
            <li class="nav-item">
                <a href="{{ route('dashboard.main') }}" class="nav-link {{ $menu == 'dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('report.main') }}" class="nav-link  {{ $menu == 'laporan arus kas' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>Lihat Laporan Keuangan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('jurnal.main') }}" class="nav-link  {{ $menu == 'jurnal' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p>Jurnal</p>
                </a>
            </li>
            @if($role == 'superadmin' || $role == 'admin')
            <li class="nav-header">TRANSAKSI</li>
            {{-- <li class="nav-item">
                <a href="{{ route('purchase.new-item') }}" class="nav-link {{ $menu == 'pembelian baru' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-truck-loading"></i>
                    <p>Pembelian Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('purchase.restock') }}" class="nav-link {{ $menu == 'penambahan stok' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>Penambahan Stok</p>
                </a>
            </li> --}}
            <li class="nav-item">
                <a href="{{ route('purchase.restock') }}" class="nav-link {{ $menu == 'pembelian barang' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>Pembelian Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('cashier.main') }}" class="nav-link {{ $menu == 'kasir' || $menu == 'edit penjualan' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cash-register"></i>
                    <p>Kasir</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('debt.main') }}" class="nav-link {{ $menu == 'hutang' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Hutang</p>
                    @isset($notifHutang)
                    <span class="badge badge-danger">{{ $notifHutang }}</span>
                    @endisset
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('receivables.main') }}" class="nav-link {{ $menu == 'piutang' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-sign-in-alt"></i>
                    <p>Piutang</p>
                    @isset($notifPiutang)
                    <span class="badge badge-danger">{{ $notifPiutang }}</span>
                    @endisset
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transaction.main') }}" class="nav-link {{ $menu == 'transaksi umum' ? 'active' : '' }}">
                    <i class="nav-icon fab fa-slack-hash"></i>
                    <p>Transaksi Umum</p>
                </a>
            </li>
            <li class="nav-header">MASTER DATA</li>
            <li class="nav-item">
                <a href="{{ route('category-product.main') }}" class="nav-link {{ $menu == 'kategori barang' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tags"></i>
                    <p>Data Kategori Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transaction-category.main') }}" class="nav-link {{ $menu == 'kategori transaksi' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-hashtag"></i>
                    <p>Data Kategori Transaksi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('student.main') }}" class="nav-link {{ $menu == 'siswa' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-graduate"></i>
                    <p>Data Siswa</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('vendor.main') }}" class="nav-link {{ $menu == 'vendor' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-hands-helping"></i>
                    <p>Data Vendor</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('product.main') }}" class="nav-link {{ $menu == 'barang' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Data Barang</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('purchase.main') }}" class="nav-link {{ $menu == 'pembelian' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>Data Pembelian</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('order.main') }}" class="nav-link {{ $menu == 'penjualan' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-store"></i>
                    <p>Data Penjualan</p>
                </a>
            </li>
            <li class="nav-header">PENGATURAN</li>
            <li class="nav-item">
                <a href="{{ route('saldo.main') }}" class="nav-link {{ $menu == 'saldo awal' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-money-bill-wave"></i>
                    <p>Saldo</p>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{ route('user.main') }}" class="nav-link {{ $menu == 'akun pengguna' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Akun</p>
                </a>
            </li> --}}
            @endif
            @endif
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
