@extends('layouts.main')

@push('append-styles')
@include('layouts.dependencies.styles-daterange')
<style>
    .aksi{
        margin-left: auto;
    }
    .money{
        text-align: end
    }
</style>
@endpush

@section('contents')
<div class="row preload-page mt-3" style="display: none;">
    <div class="col-xl-12 d-flex justify-content-center">
        <img src="{{ asset('assets/dist/img/preload.gif') }}" alt="">
    </div>
</div>
<div class="main-page">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('report.export') }}" method="post" id="filterForm" target="_blank">
                        @csrf
                        <input type="text" name="dates" class="form-control filter">
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Laporan Arus Kas <span id="periode"></span></h3>
                    <div class="aksi">
                        <button type="submit" name="export" value="excel" form="filterForm" class="btn btn-success btn-sm">Export</button>
                        <button type="submit" name="export" value="print" form="filterForm" class="btn btn-danger btn-sm">PDF</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th
                                style="width: 70%; text-align:center;"
                                >Keterangan</th>
                                <th style="width: 30%; text-align:center;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="reportBody">
                        </tbody>
                        <tfoot id="reportFooter">
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
        {{-- <div class="col"><h1>Dalam Manitenance</h1></div> --}}
    </div>
</div>
<div class="other-page"></div>
<div class="modal-page"></div>
@endsection

@push('append-scripts')
@include('layouts.dependencies.scripts-daterange')
<script>
    var preload = $('.preload-page')
    var mainPage = $('.main-page')
    var otherPage = $('.other-page')
    var modalPage = $('.modal-page')

    var table;
    var dataFilter = [];

    $('.filter').each(function() {
        dataFilter[$(this).attr('name')] = $(this).val()
    })
    $('.filter').change(function(){
        dataFilter[$(this).attr('name')] = $(this).val()
        loadData(dataFilter['dates'])
    })

    $(document).ready(function () {
        $('input[name="dates"]').daterangepicker({
            locale: {
                // format: 'DD/MM/YYYY',
                format: 'DD MMM YYYY',
                weekLabel: "M",
                daysOfWeek: ["Mg","Sen","Sel","Rab","Kam","Jum","Sab"],
                monthNames: ["Januari","Februari","Maret","April","Mei","Juni","Juli","Augustus","September","Oktober","November","Desember"],
            },
            startDate: moment().subtract(1, 'M'),
            endDate: moment()
        })
    });

    function loadData(dates){
        $('#reportBody').html(`
        <tr>
            <th colspan="2" style="text-align: center"><h5>Menunggu ...</h5></th>
        </tr>`)
        $.get("{{ route('report.main') }}",{dates})
        .done(function(result){
            var periode = result.periode
            var dPenerimaan = result.arus_kas_operasional.penerimaan
            var dPiutang = result.arus_kas_operasional.piutang
            var dPengeluaran = result.arus_kas_operasional.pengeluaran
            var dHutang = result.arus_kas_operasional.hutang
            var dTotalOpr = result.arus_kas_operasional.total_operasional

            $('#periode').html('Periode '+periode)



            var tr = `
            <tr>
                <th><h5><strong>SALDO AWAL PERIODE</strong></h5></th>
                <th class="money">${formatRibu(result.saldo_awal_periode)}</th>
            </tr>
            `
            tr += `<tr>
                <th colspan="2"><h6><strong>A. Arus Kas Kegiatan Operasional</strong></h6></th>
            </tr>`
            if(dPenerimaan){
                var jumlahDpm = parseInt(dPenerimaan)
                tr += `
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Penerimaan</td>
                    <td class="money">${formatRibu(jumlahDpm)}</td>
                </tr>
                `
            }
            if(dPiutang){
                var jumlahDpt = parseInt(dPiutang)
                tr += `
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piutang</td>
                    <td class="money">${formatRibu(jumlahDpt)}</td>
                </tr>
                `
            }
            if(dPengeluaran){
                var jumlahDpg = parseInt(dPengeluaran)
                tr += `
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Pengeluaran)</td>
                    <td class="money">-${formatRibu(jumlahDpg)}</td>
                </tr>
                `
            }
            if(dHutang){
                var jumlahDht = parseInt(dHutang)
                tr += `
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Hutang)</td>
                    <td class="money">-${formatRibu(jumlahDht)}</td>
                </tr>
                `
            }
            var totalOpr = parseInt(dTotalOpr)
            tr += `
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Operasional</td>
                <td class="money">${formatRibu(totalOpr)}</td>
            </tr>
            `

            $('#reportBody').html(tr)

            var trTotal = `
            <tr>
                <th><h5><strong>PERGERAKAN KAS</strong></h5></th>
                <th class="money">${formatRibu(result.pergerakan_kas)}</th>
            </tr>
            <tr>
                <th><h5><strong>SALDO AKHIR PERIODE</strong></h5></th>
                <th class="money">${formatRibu(result.saldo_akhir_periode)}</th>
            </tr>
            `
            $('#reportFooter').html(trTotal)
        })
        .fail(function(xhr,status,error){
            Swal.fire('Error','Terjadi Kesalahan!','error')
        })
    }

    function loadPage(thisPage){
        thisPage.hide()
        preload.show();
    }
</script>
@endpush
