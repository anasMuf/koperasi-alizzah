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
                    <input type="text" name="tanggal" class="form-control filter">
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Laporan Arus Kas <span id="periode"></span></h3>
                    <div class="aksi"></div>
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
                            <tr>
                                <th><h5><strong>TOTAL ARUS KAS</strong></h5></th>
                                <th class="money"></th>
                            </tr>
                            <tr>
                                <th><h5><strong>SALDO AWAL PERIODE</strong></h5></th>
                                <th class="money"></th>
                            </tr>
                            <tr>
                                <th><h5><strong>SALDO AKHIR PERIODE</strong></h5></th>
                                <th class="money"></th>
                            </tr>
                            <tr>
                                <th><h5><strong>PERUBAHAN KAS</strong></h5></th>
                                <th class="money"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
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
        loadData(dataFilter['tanggal'])
    })

    $(document).ready(function () {
        $('input[name="tanggal"]').daterangepicker({
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
            console.log(result);
            var periode = result.periode
            var dPenerimaan = result.penerimaan
            var dPengeluaran = result.pengeluaran

            $('#periode').html('Periode '+periode)

            var trDpm = `<tr>
                <th colspan="2"><h5><strong>PENERIMAAN KAS</strong></h5></th>
            </tr>`
            var dPmAvail = dPenerimaan.filter(val => val.jumlah != null)
            var jumlahDpm
            dPmAvail.forEach(function(element,i) {
                jumlahDpm = parseInt(element.jumlah)
                trDpm += `
                <tr>
                    <td>${i+1}. ${element.keterangan}</td>
                    <td class="money">${jumlahDpm.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>
                </tr>
                `
            });

            var trDpg = `<tr>
                <th colspan="2"><h5><strong>PENGELUARAN KAS</strong></h5></th>
            </tr>`
            var dPgAvail = dPengeluaran.filter(val => val.jumlah != null)
            var jumlahDpg
            dPgAvail.forEach(function(element,i) {
                jumlahDpg = parseInt(element.jumlah)
                trDpg += `
                <tr>
                    <td>${i+1}. ${element.keterangan}</td>
                    <td class="money">${jumlahDpg.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>
                </tr>
                `
            });

            $('#reportBody').html(trDpm+trDpg)

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
