<style>
    .aksi-variant {
        text-align: center;
        margin-top: 32px;
        margin-bottom: auto;
    }
    .list-product {
        background-color: #fff;
        position: absolute;
        z-index: 100;
        width: 90%;
        margin-top: -1px;
        border: 1px solid #ccc;
        border-bottom-left-radius: 7px;
        border-bottom-right-radius: 7px;
    }
    .list-product > ul{
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .list-product > ul > li{
        cursor: pointer;
        padding: 10px;
    }
    .list-product > ul > li:hover{
        background-color: #ccc;
    }
    .form-transparent{
        text-align: end;
        border: none;
        padding-right: 0;
    }
    input#amount,input#sisaBayar{
        background-color: transparent;
    }
    input#amount{
        font-size: 2rem;
    }
</style>
<div class="row">
    <form class="formData" style="display: contents;">
        @csrf
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Barang</h3>
                </div>
                <div class="card-body data-product">
                    <div class="form-group"
                    >
                        <label for="name_product">Nama Barang</label>
                        <input type="text" name="name_product" id="name_product"
                        class="form-control" placeholder="Masukkan Nama Barang">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori Barang</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">.:: Pilih Kategori Barang ::.</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-flex">
                        <div class="is_variant mr-5">
                            <input type="checkbox" name="is_variant" id="is_variant">
                            <label for="is_variant">Tambahkan varian barang</label>
                        </div>
                    </div>
                    <div class="product_variant_container" style="display: none;">
                        <div class="product_variant row" data-variant="0">
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name_product_variant">Nama Varian Barang</label>
                                            <input type="text" name="name_product_variant[]" id="name_product_variant_0" class="form-control" data-variant="0" placeholder="Masukkan Nama Barang Variant" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="stock">Stok</label>
                                            <input type="number" name="stock[]" id="stock_0" min="0" class="form-control" data-variant="0" placeholder="Masukkan Stok" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="purchase_price">Harga Beli Satuan</label>
                                            <input type="text" name="purchase_price[]" id="purchase_price_0" class="form-control" data-variant="0" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Beli Satuan" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1 aksi-variant">
                                <button type="button" class="btn btn-primary" onclick="tambahVariant()" disabled>+</button>
                            </div>
                        </div>
                    </div>
                    <div class="product">
                        <div class="form-group">
                            <label for="stock">Stok</label>
                            <input type="number" name="stock" id="stock" min="0" class="form-control" placeholder="Masukkan Stok">
                        </div>
                        <div class="form-group">
                            <label for="purchase_price">Harga Beli Satuan</label>
                            <input type="text" name="purchase_price" id="purchase_price" class="form-control" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Beli Satuan">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('product.main') }}" class="btn btn-default">Kembali</a>
                    <button type="button" class="btn btn-secondary" onclick="reset()">Batal</button>
                    <button type="button" class="btn btn-success" onclick="simpan($('.formData'))">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    var index = 0;
    var subtotals = [];
    var from_page = null;

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

    function tambahVariant(){
        index += 1
        $('.product_variant_container').append(
        `<div class="product_variant appended row" data-variant="${index}">
            <div class="col-11">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_product_variant">Nama Varian Barang</label>
                            <input type="text" name="name_product_variant[]" id="name_product_variant_${index}" data-variant="${index}" class="form-control" placeholder="Masukkan Nama Varian Barang">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="stock">Stok</label>
                            <input type="number" name="stock[]" id="stock_${index}" data-variant="${index}" min="0" class="form-control" placeholder="Masukkan Stok">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="purchase_price">Harga Beli Satuan</label>
                            <input type="text" name="purchase_price[]" id="purchase_price_${index}" data-variant="${index}" class="form-control" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Beli Satuan">
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

    function reset(){
        $('input').val('')
        $('select').val('')
        $('#is_variant').attr('checked',false)
        $('#is_variant').trigger('change')
    }

    function simpan(formData){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('product.store') }}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success){
                    Swal.fire('Success',response.message,'success')
                    .then((result) => {
                        if(result.isConfirmed){
                            location.reload()
                        }
                    })
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
