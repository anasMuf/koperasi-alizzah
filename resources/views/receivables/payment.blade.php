<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pembayaran Piutang Anggota</h3>
            </div>
            <div class="card-body">
                <table style="with: 100%">
                    <tr>
                        <td>Anggota</td>
                        <td>:</td>
                        <td>{{ $data ? $data->member->name : '' }}</td>
                    </tr>
                    <tr>
                        <td>Tgl Piutang</td>
                        <td>:</td>
                        <td>{{ $data ? date('d-m-Y',strtotime($data->created_at)) : '' }}</td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->receivables_member_payments as $item)
                            <tr>
                                <td>{{ date('d-m-Y',strtotime($data->created_at)) }}</td>
                                <td style="text-align: end">{{ number_format($item->amount,0,',','.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align: center">Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td style="text-align: end;">{{ $data ? number_format($data->total,0,',','.') : '' }}</td>
                        </tr>
                        <tr>
                            <td>Terbayar</td>
                            <td style="text-align: end;">{{ $data ? number_format($data->terbayar,0,',','.') : '' }}</td>
                        </tr>
                        <tr>
                            <td>Belum Dibayar</td>
                            <td style="text-align: end;">{{ $data ? number_format($data->total - $data->terbayar,0,',','.') : '' }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form</h3>
            </div>
            <form class="formData">
                @csrf
                <input type="hidden" name="id" value="{{ $data ? $data->id : '' }}">
                <div class="card-body">
                    <div class="form-group">
                        <label for="paid_at">Tanggal Bayar</label>
                        <input type="date" name="paid_at" id="paid_at" class="form-control" placeholder="Masukkan taggal bayar" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="amount">Nominal yg dibayarkan</label>
                        <br>
                        <small>sisa piutang: <span id="sisa_piutang">{{ $data ? number_format($data->total-$data->terbayar,0,',','.') : '0' }}</span></small>
                        <small id="statusPiutang"></small>
                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Masukkan nominal yg dibayar" onkeyup="countAmount(this)">
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

    function countAmount(el){
        var sisaHutang = parseInt($('#sisa_piutang').text().replaceAll('.',''))
        var amount = parseInt(el.value.replaceAll('.',''))
        var result = sisaHutang - amount

        if(result == 0){
            $('#statusPiutang').text('LUNAS').css('color','green')
            $('#btnSave').prop('disabled',false)
        }else if(amount > sisaHutang){
            $('#statusPiutang').text('Nominal Tidak Sesuai').css('color','red')
            Swal.fire('Opps!!', 'jumlah pembayaran melebihi piutang', 'warning')
            $('#btnSave').prop('disabled',true)
        }else{
            $('#statusPiutang').text('')
            $('#btnSave').prop('disabled',false)
        }

        return formatNumber(el)
    }

    function simpan(formData){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('receivables.payReceivables') }}",
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
