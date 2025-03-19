<div class="row">
    <form class="formData" style="display: contents;">
        @csrf
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Input Saldo</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="trx_date">Tanggal</label>
                        <input type="date" name="trx_date" id="trx_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="transaction_category_id">Kategori</label>
                        <select name="transaction_category_id" id="transaction_category_id" class="form-control" required>
                            <option value="">.:: Pilih Kategori ::.</option>
                            @foreach ($transaction_categories as $transaction_category)
                                <option value="{{ $transaction_category->id }}">{{ $transaction_category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambah_saldo">Saldo</label>
                        <input type="text" name="tambah_saldo" id="tambah_saldo" class="form-control" placeholder="Masukkan nilai saldo" onkeyup="formatNumber(this)">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-secondary" onclick="back()">Kembali</button>
                    <button type="button" class="btn btn-success" onclick="simpan($('.formData'))">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function formatNumber(el) {
        return el.value = el.value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function back(){
        loadPage(otherPage)
        mainPage.show()
        preload.hide()
    }

    function simpan(formData){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('saldo.store') }}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success){
                    Swal.fire('Success',response.message,'success')
                    back()
                    table.ajax.reload()
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
