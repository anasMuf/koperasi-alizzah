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
                        <label for="name">Nama Vendor</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan Nama Vendor" value="{{ $data ? $data->name : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea name="address" id="address" class="form-control" placeholder="Masukkan Alamat">{{ $data ? $data->address : '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Masukkan Nomor Telepon" value="{{ $data ? $data->phone : '' }}">
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
            url: "{{ route('vendor.store') }}",
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
