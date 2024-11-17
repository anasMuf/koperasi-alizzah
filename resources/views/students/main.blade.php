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
                <div class="card-body">
                    <input type="text" name="tanggal" class="form-control filter">
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Data</h3>
                    <div class="aksi">
                        <button type="button" class="btn btn-primary btn-xs" onclick="openData()">Tambah</button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
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
    var dataFilter = [];

    $('.filter').each(function() {
        dataFilter[$(this).attr('name')] = $(this).val()
    })
    $('.filter').change(function(){
        dataFilter[$(this).attr('name')] = $(this).val()
        table.ajax.reload()
    })

    $(document).ready(function () {
        let url = "{{ route('student.main') }}";
        table = new DataTable('#table', {
            ajax: {
                url: url,
                data: function(d) {
                    return $.extend({}, d, dataFilter);
                }
            },
            processing: true,
            serverSide: true,
            scrollX: true,
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false},
                {data: 'name' , name: 'name'},
                {data: 'action' , name: 'action', orderable: false, searchable: false},
            ]
        });

        $('input[name="tanggal"]').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                weekLabel: "M",
                daysOfWeek: ["Mg","Sen","Sel","Rab","Kam","Jum","Sab"],
                monthNames: ["Januari","Februari","Maret","April","Mei","Juni","Juli","Augustus","September","Oktober","November","Desember"],
            },
            startDate: moment().subtract(1, 'M'),
            endDate: moment()
        })
    });

    function loadPage(thisPage){
        thisPage.hide()
        preload.show();
    }

    function openData(id=null){
        loadPage(mainPage)
        $.get("{{ route('student.form') }}",{id})
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

    function deleteData(id){
        Swal.fire({
            title: "Data akan dihapus!",
            text: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak',
            confirmButtonColor: "#d33",
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ route('student.delete') }}",
                    data: {
                        id,
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.success) {
                            Swal.fire('Success',result.message,'success')
                            table.ajax.reload()
                            return
                        }
                        return Swal.fire("Gagal", "Data gagal dihapus", "warning");
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error','Terjadi Kesalahan!','error')
                    }
                });
            }
        });
    }
</script>
@endpush
