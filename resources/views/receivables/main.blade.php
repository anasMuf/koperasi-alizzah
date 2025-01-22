@extends('layouts.main')

@push('append-styles')
@include('layouts.dependencies.styles-datatable')
<style>
    .aksi{
        margin-left: auto;
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
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Data Piutang dari Siswa</h3>
                </div>
                <div class="card-body">
                    <table id="tableSiswa" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th>No</th>
                                <th>Invoice</th>
                                <th>Siswa</th>
                                <th>Total</th>
                                <th>Terbayar</th>
                                <th>Sisa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align:right !important;">Total:</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Data Piutang dari Anggota</h3>
                    <div class="aksi">
                        <button type="button" class="btn btn-primary btn-sm" onclick="openDataOther()">Tambah</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableMember" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Total</th>
                                <th>Terbayar</th>
                                <th>Sisa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2" style="text-align:right !important;">Total:</th>
                                <th></th>
                                <th></th>
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

    var tableSiswa;
    var tableMember;

    $(document).ready(function () {
        let urlSiswa = "{{ route('receivables.main') }}";
        tableSiswa = new DataTable('#tableSiswa', {
            ajax: {
                url: urlSiswa,
            },
            processing: true,
            serverSide: false,
            scrollX: true,
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false},
                {data: 'invoice_' , name: 'invoice_'},
                {data: 'student.name' , name: 'student.name', defaultContent: ''},
                {data: 'total_' , name: 'total_'},
                {data: 'terbayar_' , name: 'terbayar_'},
                {data: 'sisa_' , name: 'sisa_'},
                {data: 'status' , name: 'status'},
                {data: 'action' , name: 'action', orderable: false, searchable: false},
            ],
            footerCallback: function(row, data, start, end, display) {
                let api = this.api();

                // Remove the formatting to get integer data for summation
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[^\d-]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                // Total over all pages
                let total3 = api
                    .column(3)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let total4 = api
                    .column(4)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let total5 = api
                    .column(5)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Total over this page
                let pageTotal3 = api
                    .column(3, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let pageTotal4 = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let pageTotal5 = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Update footer
                api.column(3).footer().innerHTML =
                'total perhalaman '+formatRibu(pageTotal3) + ', total keseluruhan ' + formatRibu(total3);
                api.column(4).footer().innerHTML =
                'total perhalaman '+formatRibu(pageTotal4) + ', total keseluruhan ' + formatRibu(total4);
                api.column(5).footer().innerHTML =
                'total perhalaman '+formatRibu(pageTotal5) + ', total keseluruhan ' + formatRibu(total5);
            },
        });

        let urlMember = "{{ route('receivables.member') }}";
        tableMember = new DataTable('#tableMember', {
            ajax: {
                url: urlMember,
            },
            processing: true,
            serverSide: false,
            scrollX: true,
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false},
                {data: 'member.name' , name: 'member.name', defaultContent: ''},
                {data: 'total_' , name: 'total_'},
                {data: 'terbayar_' , name: 'terbayar_'},
                {data: 'sisa_' , name: 'sisa_'},
                {data: 'status' , name: 'status'},
                {data: 'action' , name: 'action', orderable: false, searchable: false},
            ],
            footerCallback: function(row, data, start, end, display) {
                let api = this.api();

                // Remove the formatting to get integer data for summation
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[^\d-]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                // Total over all pages
                let total2 = api
                    .column(2)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let total3 = api
                    .column(3)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let total4 = api
                    .column(4)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Total over this page
                let pageTotal2 = api
                    .column(2, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let pageTotal3 = api
                    .column(3, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                let pageTotal4 = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Update footer
                api.column(2).footer().innerHTML =
                'total perhalaman '+formatRibu(pageTotal2) + ', total keseluruhan ' + formatRibu(total2);
                api.column(3).footer().innerHTML =
                'total perhalaman '+formatRibu(pageTotal3) + ', total keseluruhan ' + formatRibu(total3);
                api.column(4).footer().innerHTML =
                'total perhalaman '+formatRibu(pageTotal4) + ', total keseluruhan ' + formatRibu(total4);
            },
        })
    });

    function loadPage(thisPage){
        thisPage.hide()
        preload.show();
    }

    function openData(id=null){
        loadPage(mainPage)
        $.get("{{ route('receivables.form') }}",{id})
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
    function openDataOther(id=null){
        loadPage(mainPage)
        var url = "{{ route('receivables.createMember') }}"
        if(id){
            url = "{{ route('receivables.paymentMember') }}"
        }
        $.get(url,{id})
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
