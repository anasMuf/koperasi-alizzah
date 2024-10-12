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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Produk</h3>
                </div>
                <div class="card-body data-product">
                    <input type="hidden" name="id_product" id="id_product">
                    <div class="form-group" id="formProductName">
                        <label for="name_product">Nama Produk</label>
                        <input type="text" name="name_product" id="name_product" onkeyup="findProduct(this.value)" class="form-control" placeholder="Masukkan Nama Produk">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="is_variant" id="is_variant">
                        <label for="is_variant">Tambahkan varian produk</label>
                    </div>
                    <div class="product_variant_container" style="display: none;">
                        <div class="product_variant row" data-variant="0">
                            <input type="hidden" name="product_variant_id[]" id="product_variant_id_0" data-variant="0">
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name_product_variant">Nama Varian Produk</label>
                                            <input type="text" name="name_product_variant[]" id="name_product_variant_0" class="form-control" data-variant="0" placeholder="Masukkan Nama Produk Variant" disabled>
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
                                            <label for="price">Harga Beli Satuan</label>
                                            <input type="text" name="price[]" id="price_0" class="form-control" data-variant="0" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Beli Satuan" disabled>
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
                            <label for="price">Harga Beli Satuan</label>
                            <input type="text" name="price" id="price" class="form-control" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Beli Satuan">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pembelian</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="vendor_id">Pemasok</label>
                        <select name="vendor_id" id="vendor_id" class="form-control">
                            <option value="">.:: Pilih Pemasok ::.</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <table style="width: 100%">
                        <tr>
                            <th style="width: 40%;"><h2>Total</h2></th>
                            <th style="width: 10%;"><h2>Rp</h2></th>
                            <th style="width: 50%; text-align: end;">
                                <h2>
                                    <input type="text" name="total" id="amount" class="form-control form-transparent" value="0" readonly>
                                </h2>
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 40%;">Dibayar</td>
                            <td style="width: 10%;">Rp</td>
                            <td style="width: 50%; text-align: end;">
                                <input type="text" name="terbayar" id="terbayar" class="form-control form-transparent" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%;">Sisa</td>
                            <td style="width: 10%;">Rp</td>
                            <td style="width: 50%; text-align: end;">
                                <input type="text" name="sisaBayar" id="sisaBayar" class="form-control form-transparent" value="0" readonly>
                            </td>
                        </tr>
                    </table>
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
            <input type="hidden" name="product_variant_id[]" id="product_variant_id_${index}" data-variant="${index}">
            <div class="col-11">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_product_variant">Nama Varian Produk</label>
                            <input type="text" name="name_product_variant[]" id="name_product_variant_${index}" data-variant="${index}" class="form-control" placeholder="Masukkan Nama Varian Produk">
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
                            <label for="price">Harga Beli Satuan</label>
                            <input type="text" name="price[]" id="price_${index}" data-variant="${index}" class="form-control" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Beli Satuan">
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
    // ==========================================
    function findProduct(value){
        if(value.length < 2){
            $.each($('.list-product'), function (i, v) {
                v.remove()
            });
            return
        }
        $.each($('.list-product'), function (i, v) {
            v.remove()
        });
        $.get("{{ route('product.search') }}",{name:value})
        .done(function(result){
            $('#formProductName').append(`<div class="list-product"><ul></ul></div>`)
            if(result.success && result.data.length > 0){
                $('.list-product>ul').html('')
                var data='';
                $.each(result.data, function (i, v) {
                    data += `<li data-product="${v.id}" onclick="selectedProduct(${v.id})">${v.name}</li>`
                });
                $('.list-product>ul').append(data)
            }else{
                $.each($('.list-product'), function (i, v) {
                    v.remove()
                });
            }
        })
        .fail(function (xhr,status,error) {
            Swal.fire('Error','Terjadi Kesalahan!','error')
        })
    }

    $('#terbayar').focusin(function (e) {
        e.preventDefault();
        if($(this).val() == 0){
            $(this).val('')
        }
    });
    $('#terbayar').focusout(function (e) {
        e.preventDefault();
        if($(this).val() == 0){
            $(this).val('0')
        }
    });

    function selectedProduct(id) {
        $.each($('.list-product'), function (i, v) {
            v.remove()
        });
        $.get("{{ route('product.selected') }}",{id})
        .done(function(result){
            fillProduct(result.data)
        })
        .fail(function (xhr,status,error) {
            Swal.fire('Error','Terjadi Kesalahan!','error')
        })
    }

    function fillProduct(data){
        var isVariants = data.is_variants
        var product = data.product
        $('#id_product').val(product.id)
        $('#name_product').val(product.name)

        if(isVariants){
            $.each(product.product_variants, function (i, v) {
                tambahVariant()
                if(i === (product.product_variants.length-1)){
                    hapusVariant(i+1)
                }
                $('#product_variant_id_'+i).val(v.name)
                $('#name_product_variant_'+i).val(v.name)
                $('#stock_'+i).val(v.stock)
                // $('#price_'+i).val(String(parseInt(v.price)))
                // formatNumber($('#price_'+i)[0])
            });
        }else{
            $.each(product.product_variants, function (i, v) {
                $('.product #stock').val(v.stock)
                // $('.product #price').val(String(parseInt(v.price)))
                // formatNumber($('.product #price')[0])
            });
        }
    }

    function amountPurchases(stock,price){
        var amount = stock*price;
        $('#amount').val(amount)
        formatNumber($('#amount')[0])
    }
    $('.product #stock').keyup(function () {
        var stock = parseInt($(this).val())
        var price = ($('.product #price').val().length > 1) ? parseInt($('.product #price').val().replaceAll('.','')) : 0
        amountPurchases(stock,price)
    })
    $('.product #price').keyup(function () {
        var stock = ($('.product #stock').val().length > 1) ? parseInt($('.product #stock').val()) : 0
        var price = parseInt($(this).val().replaceAll('.',''))
        amountPurchases(stock,price)
    })

    function sisaBayar(terbayar,total){
        var sisaBayar = total-terbayar;
        if(sisaBayar >= total || sisaBayar < 0){
            sisaBayar = 0;
        }
        $('#sisaBayar').val(sisaBayar);
        formatNumber($('#sisaBayar')[0])
    }
    $('#terbayar').keyup(function () {
        var terbayar = parseInt(String($(this).val()).replaceAll('.',''))
        var total = ($('#amount').val().length > 1) ? parseInt($('#amount').val().replaceAll('.','')) : 0
        formatNumber($(this)[0])
        sisaBayar(terbayar,total)
    })

    function simpan(formData,purchase=false){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('purchase.store') }}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success && !purchase){
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
