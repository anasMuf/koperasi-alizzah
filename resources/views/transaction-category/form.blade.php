<style>
    .aksi-variant {
        text-align: center;
        margin-top: 32px;
        margin-bottom: auto;
    }
</style>
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
                        <label for="name">Nama Kategori Transaksi</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan Nama Kategori Transaksi" value="{{ $data ? $data->name : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="type">Tipe</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">.:: Pilih Tipe ::.</option>
                            <option value="pemasukan" {{ $data && $data->type == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ $data && $data->type == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" cols="30" rows="10">{{ $data ? $data->description : '' }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-secondary" onclick="back()">Kembali</button>
                    <button type="button" class="btn btn-success" onclick="simpan($('.formData'))">Simpan</button>
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

    function simpan(formData){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('transaction-category.store') }}",
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
