@extends('layouts.main')

@push('append-styles')
<style>
    .aksi{
        margin-left: auto;
    }
</style>
@endpush

@section('contents')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Penjualan dan Piutang</h3>
                <div class="aksi">
                    @php
                        $tahunSekarang = date('Y');
                        $tahuns = [];
                        for ($i=0; $i <= 1; $i++) {
                            array_push($tahuns,$tahunSekarang-$i);
                        }
                    @endphp
                    <select name="tahun" id="tahunPenjualanPiutang" class="tahun">
                        @foreach ($tahuns as $item)
                            <option value="{{ $item }}" {{ $item == $tahunSekarang ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="penjualanPiutang" style="height: 357px; width:auto"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Posisi Saldo</h3>
                <div class="aksi">
                    @php
                        $tahunSekarang = date('Y');
                        $tahuns = [];
                        for ($i=0; $i <= 1; $i++) {
                            array_push($tahuns,$tahunSekarang-$i);
                        }
                    @endphp
                    <select name="tahun" id="tahunPosisiSaldo" class="tahun">
                        @foreach ($tahuns as $item)
                            <option value="{{ $item }}" {{ $item == $tahunSekarang ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
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

    $('#tahunPenjualanPiutang').change(function(){
        penjualanPiutangChart()
    })
    $('#tahunPosisiSaldo').change(function(){
        posisiSaldoChart()
    })
</script>
@endpush
