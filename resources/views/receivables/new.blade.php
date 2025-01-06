<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Piutang Anggota</h3>
            </div>
            <form class="formData">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="date">Tanggal</label>
                        <input type="date" name="date" id="date" class="form-control" placeholder="Masukkan taggal bayar" value="{{ $data ? date('Y-m-d',strtotime($data->date)) : date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="member">Anggota</label>
                        <select name="member" id="member" class="form-control">
                            <option value="">.:: Pilih Anggota ::.</option>
                            @foreach ($members as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Keterangan</label>
                        <textarea name="description" id="description" class="form-control" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="amount">Nominal Piutang</label>
                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Masukkan nominal piutang" onkeyup="formatNumber(this)">
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
            url: "{{ route('receivables.newReceivables') }}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success){
                    Swal.fire('Success',response.message,'success')
                    back()
                    tableMember.ajax.reload()
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
