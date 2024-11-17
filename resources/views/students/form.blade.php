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
                        <label for="no_induk">Nomor Induk</label>
                        <input type="text" name="no_induk" id="no_induk" class="form-control" placeholder="Masukkan Nomor Induk" value="{{ $data ? $data->no_induk : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Siswa</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan Nama Siswa" value="{{ $data ? $data->name : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <div class="choise-container">
                            <div class="choise">
                                <input type="radio" name="gender" id="genderL" value="L" {{ ($data && $data->gender == 'L') ? 'checked' : '' }}>
                                <label for="genderL">Laki-laki</label>
                            </div>
                            <div class="choise">
                                <input type="radio" name="gender" id="genderP" value="P" {{ ($data && $data->gender == 'P') ? 'checked' : '' }}>
                                <label for="genderP">Perempuan</label>
                            </div>
                        </div>
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
            url: "{{ route('student.store') }}",
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
