<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form</h3>
            </div>
            <form class="formData">
                @csrf
                <input type="hidden" name="id" value="{{ $data ? $data->id : '' }}">
                <div class="card-body">
                    <div class="form-group">
                        <label for="trx_date">Tanggal Transaksi</label>
                        <input type="date" name="trx_date" id="trx_date" class="form-control" placeholder="Masukkan taggal" value="{{ $data ? date('Y-m-d',strtotime($data->trx_date)) : date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="type">Tipe Transaksi</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">.:: Pilih Tipe Transaksi ::.</option>
                            <option value="pemasukan" {{ $data && $data->type == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ $data && $data->type == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transaction_category_id">Kategori</label>
                        <select name="transaction_category_id" id="transaction_category_id" class="form-control" required>
                            <option value="">.:: Pilih Kategori ::.</option>
                            @foreach ($transaction_categories as $transaction_category)
                                <option value="{{ $transaction_category->id }}">{{ $transaction_category->type }} || {{ $transaction_category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ $data ? $data->description : '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="amount">Nominal</label>
                        @php
                            if($data && $data->type == 'pemasukan'){
                                $nominal = number_format($data->debit,0,',','.');
                            }elseif($data && $data->type == 'pengeluaran'){
                                $nominal = number_format($data->credit,0,',','.');
                            }
                        @endphp
                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Masukkan nominal" value="{{ $data ? $nominal : '' }}" onkeyup="formatNumber(this)">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-secondary" onclick="back()">Kembali</button>
                    <button type="button" class="btn btn-success" id="btnSave" onclick="simpan($('.formData'))">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function back(){
        loadPage(otherPage)
        mainPage.show()
        preload.hide()
    }

    function formatNumber(el) {
        return el.value = el.value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function simpan(formData){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('transaction.store') }}",
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
