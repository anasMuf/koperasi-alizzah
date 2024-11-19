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
                    <h3 class="card-title">Data</h3>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped table-hover" style="width: 100%">
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

    $(document).ready(function () {
        let url = "{{ route('receivables.main') }}";
        table = new DataTable('#table', {
            ajax: {
                url: url,
            },
            processing: true,
            serverSide: true,
            scrollX: true,
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false},
                {data: 'invoice_' , name: 'invoice_'},
                {data: 'student.name' , name: 'student.name'},
                {data: 'total_' , name: 'total_'},
                {data: 'terbayar_' , name: 'terbayar_'},
                {data: 'sisa_' , name: 'sisa_'},
                {data: 'status' , name: 'status'},
                {data: 'action' , name: 'action', orderable: false, searchable: false},
            ]
        });
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
</script>
@endpush
