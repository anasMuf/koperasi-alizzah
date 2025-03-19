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
                        {{-- <input type="text" name="dates" class="form-control filter"> --}}
                        <div class="row">
                            {{-- <div class="form-group col-md-6">
                                <label for="start_date">Tanggal Awal</label>
                                <input type="month" name="start_date" class="form-control filter" value="{{ date('Y-m') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date">Tanggal Akhir</label>
                                <input type="month" name="end_date" class="form-control filter" value="{{ date('Y-m') }}">
                            </div> --}}
                            <div class="form-group col-md-6">
                                <label for="year_period_id">Tahun Ajaran</label>
                                <select name="year_period_id" class="form-control filter">
                                    <option value="">.:: Pilih Tahun Ajaran ::.</option>
                                    @foreach ($year_periods as $year)
                                        <option value="{{ $year->id }}" {{ ($year->is_active) ? 'selected' : '' }}>{{ $year->name_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Laporan Keuangan <span id="periode"></span></h3>
                    <div class="aksi">
                        <button type="submit" name="export" value="excel" form="filterForm" class="btn btn-success btn-sm">Export</button>
                        <button type="submit" name="export" value="print" form="filterForm" class="btn btn-danger btn-sm">PDF</button>
                    </div>
                </div>
                <div class="card-body reports table-responsive">
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
        loadData({ ...dataFilter })
    })

    $(document).ready(function () {
        loadData({ ...dataFilter })
    });

    function loadData(dates){
        $('#reportBody').html(`
        <tr>
            <th colspan="2" style="text-align: center"><h5>Menunggu ...</h5></th>
        </tr>`)
        $.get("{{ route('report.main') }}",dates)
        .done(function(result){
            return $('.reports').html(result.content).fadeIn()
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
