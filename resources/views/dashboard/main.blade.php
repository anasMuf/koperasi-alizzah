@extends('layouts.main')

@push('append-styles')

@endpush

@section('contents')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Penjualan dan Piutang</h3>
            </div>
            <div class="card-body">
                <canvas id="penjualanPiutang" style="height: 357px; width:auto"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Posisi Saldo</h3>
            </div>
            <div class="card-body">
                <canvas id="posisiSaldo" style="height: 357px; width:auto"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Piutang Anggota</h3>
            </div>
            <div class="card-body">
                <canvas id="piutangAnggota" style="height: 357px; width:auto"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Stok barang</h3>
            </div>
            <div class="card-body">
                <canvas id="stokBarang" style="height: 357px; width:auto"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('append-scripts')
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

<script src="{{ asset('assets/dist/js/dashboards/chart.js') }}"></script>

<script>
    $(document).ready(function () {
        penjualanPiutangChart()
        posisiSaldoChart()
        piutangAnggotaChart()
        stokBarangChart()
    });
    const url = "{{ route('dashboard.data') }}";
</script>
@endpush
