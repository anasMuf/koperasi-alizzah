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
                @if(!$is_variants)
                <input type="hidden" name="product_variant_id" value="{{ $data ? $data->product_variants[0]->id : '' }}">
                @endif
                <div class="card-body">
                    <div class="form-group">
                        <label for="name_product">Nama Produk</label>
                        <input type="text" name="name_product" id="name_product" class="form-control" placeholder="Masukkan Nama Produk" value="{{ $data ? $data->name : '' }}">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="is_variant" id="is_variant" {{ $is_variants ? 'checked' : '' }}>
                        <label for="is_variant">Tambahkan varian produk</label>
                    </div>
                    <div class="product_variant_container" @if(!$is_variants) style="display: none;" @endif>
                        @if($data)
                            @foreach ($data->product_variants as $key => $product_variant )
                                <div class="product_variant row" data-variant="{{ $key }}">
                                    <input type="hidden" name="product_variant_id[]" data-variant="{{ $key }}" value="{{ $product_variant->id }}" {{ !$is_variants ? 'disabled' : '' }}>
                                    <div class="col-11">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name_product_variant">Nama Varian Produk</label>
                                                    <input type="text" name="name_product_variant[]" id="name_product_variant" class="form-control" data-variant="{{ $key }}" placeholder="Masukkan Nama Produk Variant" value="{{ $product_variant->name }}" {{ !$is_variants ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="stock">Stok</label>
                                                    <input type="number" name="stock[]" id="stock" min="0" class="form-control" data-variant="{{ $key }}" placeholder="Masukkan Stok" value="{{ $product_variant->stock }}" {{ !$is_variants ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="price">Harga Jual Satuan</label>
                                                    <input type="text" name="price[]" id="price" class="form-control" data-variant="{{ $key }}" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Jual Satuan" value="{{ number_format($product_variant->price,0,',','.') }}" {{ !$is_variants ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1 aksi-variant">
                                        @if ($key == 0)
                                            <button type="button" class="btn btn-primary" onclick="tambahVariant()">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger" onclick="hapusVariant($key)">-</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="product_variant row" data-variant="0">
                                <div class="col-11">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name_product_variant">Nama Varian Produk</label>
                                                <input type="text" name="name_product_variant[]" id="name_product_variant" class="form-control" data-variant="0" placeholder="Masukkan Nama Produk Variant" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="stock">Stok</label>
                                                <input type="number" name="stock[]" id="stock" min="0" class="form-control" data-variant="0" placeholder="Masukkan Stok" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="price">Harga Jual Satuan</label>
                                                <input type="text" name="price[]" id="price" class="form-control" data-variant="0" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Jual Satuan" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1 aksi-variant">
                                    <button type="button" class="btn btn-primary" onclick="tambahVariant()" disabled>+</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="product" @if($is_variants) style="display: none;" @endif>
                        <div class="form-group">
                            <label for="stock">Stok</label>
                            <input type="number" name="stock" id="stock" min="0" class="form-control" placeholder="Masukkan Stok" value="{{ $data ? $data->product_variants[0]->stock : '' }}" {{ $is_variants ? 'disabled' : '' }}>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga Jual Satuan</label>
                            <input type="text" name="price" id="price" class="form-control" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Jual Satuan" value="{{ $data ? number_format($data->product_variants[0]->price,0,',','.') : '' }}" {{ $is_variants ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-secondary" onclick="back()">Kembali</button>
                    <button type="button" class="btn btn-success" onclick="simpan($('.formData'))">Simpan</button>
                    <button type="button" class="btn btn-success disabled" onclick="simpan($('.formData'),true)">Simpan dan Buat Pembelian</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#is_variant').change(function () {
        if(this.checked){
            $('.product_variant_container').show()
            $('.product_variant_container input, .product_variant_container button').removeAttr('disabled')
            $('.product').hide()
            $('.product input').prop('disabled',true)
        }else{
            $('.product_variant_container').hide()
            $('.product_variant_container input, .product_variant_container button').prop('disabled',true)
            $('.product').show()
            $('.product input').removeAttr('disabled')
        }
    })

    function formatNumber(el) {
        return el.value = el.value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    var index = 0;
    function tambahVariant(){
        index += 1
        $('.product_variant_container').append(
        `<div class="product_variant row" data-variant="${index}">
            <div class="col-11">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_product_variant">Nama Varian Produk</label>
                            <input type="text" name="name_product_variant[]" id="name_product_variant" data-variant="${index}" class="form-control" placeholder="Masukkan Nama Varian Produk">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="stock">Stok</label>
                            <input type="number" name="stock[]" id="stock" data-variant="${index}" min="0" class="form-control" placeholder="Masukkan Stok">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price">Harga Jual Satuan</label>
                            <input type="text" name="price[]" id="price" data-variant="${index}" class="form-control" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Jual Satuan">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 aksi-variant">
                <button type="button" class="btn btn-danger" onclick="hapusVariant(${index})">-</button>
            </div>
        </div>`
        )
    }

    function hapusVariant(index){
        $.each($('.product_variant'), function (i, v) {
            if(v.dataset.variant == index){
                v.remove()
            }
        });
    }

    function back(){
        loadPage(otherPage)
        mainPage.show()
        preload.hide()
    }

    function simpan(formData,purchase=false){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('product.store') }}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success && !purchase){
                    Swal.fire('Success',response.message,'success')
                    back()
                    table.ajax.reload()
                }else if(response.success && purchase){
                    // go to purchase form
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
