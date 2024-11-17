@extends('layouts.main')

@push('append-styles')
<style>
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
        <form class="formData" style="display: contents;">
            @csrf
            <input type="hidden" name="id_ledger" value="{{ $saldo_awal ? $saldo_awal->id : '' }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Input Saldo Awal</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="saldo_awal">Saldo Awal</label>
                            <input type="text" name="saldo_awal" id="saldo_awal" class="form-control" placeholder="Masukkan nilai saldo awal" value="{{ $saldo_awal ? number_format($saldo_awal->final,0,',','.') : '' }}" onkeyup="formatNumber(this)">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="cencel" class="btn btn-secondary">Batal</button>
                        <button type="button" class="btn btn-success" onclick="simpan($('.formData'))">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="other-page"></div>
<div class="modal-page"></div>
@endsection

@push('append-scripts')
<script>
    function formatNumber(el) {
        return el.value = el.value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function simpan(formData){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('order.store-saldo') }}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success){
                    Swal.fire('Success',response.message,'success')
                    location.reload()
                }
            },
            error: function (xhr,status,error) {
                if(xhr.status == 422){
                    var message = '';
                    $.each(xhr.responseJSON.errors, function (i,msg) {
                        message += msg[0]+', <br>';
                    })
                    return Swal.fire ( xhr.responseJSON.message , message, 'warning' )
                }
                Swal.fire('Error','Terjadi Kesalahan!','error')
            }
        });
    }
</script>
@endpush
