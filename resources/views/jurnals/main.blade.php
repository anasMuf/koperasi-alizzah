@extends('layouts.main')

@push('append-styles')
@include('layouts.dependencies.styles-datatable')
<style>
    .aksi{
        margin-left: auto;
    }
    table .nominal {
        text-align: end;
    }
    #table tr {
        cursor: pointer;
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
                    <form action="{{ route('jurnal.export') }}" method="post" id="filterForm" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="start_date">Tanggal Awal</label>
                                <input type="month" name="start_date" class="form-control filter">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date">Tanggal Akhir</label>
                                <input type="month" name="end_date" class="form-control filter">
                            </div>
                        </div>
                        <select name="type_transaksi" id="type_transaksi" class="form-control filter">
                            <option value="">Semua</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Jurnal Transaksi</h3>
                    <div class="aksi">
                        <button type="submit" name="export" value="print" form="filterForm" class="btn btn-danger btn-xs">Print</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Referensi</th>
                                <th>Tipe Transaksi</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align:right !important;">Total:</th>
                                <th></th>
                                <th></th>
                                <th></th>
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
@include('layouts.dependencies.scripts-datatable')
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
        table.ajax.reload()
    })

    $(document).ready(function () {
        let url = "{{ route('jurnal.main') }}";
        table = new DataTable('#table', {
            ajax: {
                url: url,
                data: function(d) {
                    return $.extend({}, d, dataFilter);
                }
            },
            lengthMenu: [10, 25, 50, 100, 500],
            processing: true,
            scrollX: true,
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false},
                {data: 'trx_date_' , name: 'trx_date_'},
                {data: 'keterangan' , name: 'keterangan', orderable: false},
                {data: 'refrence_' , name: 'refrence_'},
                {data: 'type' , name: 'type'},
                {data: 'debit' , name: 'debit', className: 'nominal'},
                {data: 'credit' , name: 'credit', className: 'nominal'},
                {data: 'final' , name: 'final', className: 'nominal'},
                // {data: 'action' , name: 'action', orderable: false, searchable: false},
            ],
            footerCallback: function(row, data, start, end, display) {
                let api = this.api();

                // Remove the formatting to get integer data for summation
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[^\d]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                // Hitung total pemasukan dan pengeluaran untuk semua halaman
                let totalPemasukan = api
                    .rows()
                    .data()
                    .toArray()
                    .reduce((sum, row) => row.type === 'pemasukan' ? sum + intVal(row.debit) : sum, 0);

                let totalPengeluaran = api
                    .rows()
                    .data()
                    .toArray()
                    .reduce((sum, row) => row.type === 'pengeluaran' ? sum + intVal(row.credit) : sum, 0);

                let total = totalPemasukan - totalPengeluaran;


                // Hitung total pemasukan dan pengeluaran untuk halaman saat ini
                let pagePemasukan = api
                    .rows({ page: 'current' })
                    .data()
                    .toArray()
                    .reduce((sum, row) => row.type === 'pemasukan' ? sum + intVal(row.debit) : sum, 0);

                let pagePengeluaran = api
                    .rows({ page: 'current' })
                    .data()
                    .toArray()
                    .reduce((sum, row) => row.type === 'pengeluaran' ? sum + intVal(row.credit) : sum, 0);

                let pageTotal = pagePemasukan - pagePengeluaran;

                // Update footer

                if(totalPemasukan == 0){
                    api.column(5).footer().innerHTML = '-'
                }else{
                    api.column(5).footer().innerHTML =
                    'total perhalaman ' + formatRibu(pagePemasukan) + ', total keseluruhan ' + formatRibu(totalPemasukan);
                }

                if(totalPengeluaran == 0){
                    api.column(6).footer().innerHTML = '-'
                }else{
                    api.column(6).footer().innerHTML =
                    'total perhalaman ' + formatRibu(pagePengeluaran) + ', total keseluruhan ' + formatRibu(totalPengeluaran);
                }

                if(totalPemasukan == 0 || totalPengeluaran == 0){
                    api.column(7).footer().innerHTML = '-'
                }else{
                    api.column(7).footer().innerHTML =
                    'total perhalaman ' + formatRibu(pageTotal) + ', total keseluruhan ' + formatRibu(total);
                }
            },
        });

        // $('input[name="tanggal"]').daterangepicker({
        //     locale: {
        //         format: 'DD/MM/YYYY',
        //         weekLabel: "M",
        //         daysOfWeek: ["Mg","Sen","Sel","Rab","Kam","Jum","Sab"],
        //         monthNames: ["Januari","Februari","Maret","April","Mei","Juni","Juli","Augustus","September","Oktober","November","Desember"],
        //     },
        //     startDate: moment().subtract(1, 'Y'),
        //     endDate: moment()
        // })
        // table.on('click','tbody tr', function () {
        //     let data = table.row(this).data();

        //     let param = data.refrence;
        //     if(param)
        //     alert('You clicked on ' + param + "'s row");
        // });
    });

    function loadPage(thisPage){
        thisPage.hide()
        preload.show();
    }

    function openData(id=null){
        loadPage(mainPage)
        $.get("{{ route('transaction.form') }}",{id})
        .done(function (result) {
            preload.hide();
            return otherPage.html(result.content).fadeIn();
        })
        .fail(function (xhr,status,error) {
            Swal.fire('Error','Terjadi Kesalahan!','error')
            mainPage.show()
            preload.hide()
        })
    }
</script>
@endpush
