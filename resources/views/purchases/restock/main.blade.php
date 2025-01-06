@extends('layouts.main')

@push('append-styles')
<style>
    .products-body{
    }
    .product-box{
        height: 140px;
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: 1px 1px #ccc;
        padding: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 15px;
        cursor: pointer;
    }
    .product-box:hover{
        background-color: #ccc;
    }
    .detail {
        display: flex;
        justify-content: space-between;
    }
    .right{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .product {
        text-align: start;
    }
    .price {
        text-align: end;
    }
    .aksi{
        padding: 10px 0px 10px 10px;;
    }
    .total{
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .total #total{
        text-align: end;
    }
    .form-transparent{
        /* text-align: start; */
        /* border: none; */
        /* padding-left: 0; */
    }
    .form-transparent[readonly]{
        background-color: transparent;
    }

    .backdrop-load {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100000;
        display: block;
        width: 100%;
        height: 100%;
        background-color: #00000061;
        display: flex;
        justify-content: center;
        padding: 400px;
    }
</style>
@endpush

@section('contents')
<div class="row">
    <div class="col-md-8 products">
        <div class="card">
            <div class="card-body">
                <h5>Barang</h5>
                <div class="serach-product">
                    <div class="form-group">
                        <input type="search" name="search_product" id="search_product" class="form-control" placeholder="Cari Barang" onsearch="loadProduct(this.value)">
                    </div>
                </div>
                <div class="row products-body">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 details">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="purchase_at">Tanggal Pembelian</label>
                    <input type="date" name="purchase_at" id="purchase_at" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="student">
                    <div class="form-group">
                        <label for="vendor_id">Vendor</label>
                        <select name="vendor_id" id="vendor_id" class="form-control">
                            <option value="">.:: Pilih Vendor ::.</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="details mb-5">
                    <h5>Detail Barang</h5>
                    <div class="detail-container">
                    </div>
                </div>
                <div class="amount">
                    <h5>Total</h5>
                    <div class="total">
                        <h3>Rp</h3>
                        <h3><span id="total">0</span></h3>
                    </div>
                    <div class="form-group">
                        <label for="dibayar">Dibayar</label>
                        <input type="text" name="dibayar" id="dibayar" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="sisa">Kembalian / Kurang</label>
                        <input type="text" name="sisa" id="sisa" class="form-control form-transparent" readonly>
                    </div>
                </div>
                <div class="form-action">
                    <button type="button" class="btn btn-default" onclick="reset()">Batal</button>
                    <button type="button" class="btn btn-success" onclick="simpan()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-page"></div>
@endsection

@push('append-scripts')
<script>
    var productsBody = $('.products-body')
    var dataProducts = []

    $(document).ready(function () {
        loadProduct()
    });

    function loadProduct(search_product=null) {
        if(search_product && search_product.length < 3){
            return
        }
        productsBody.html('')
        $.get("{{ route('cashier.products') }}", {search_product})
        .done(function(result){
            var products = result.data
            var productEl = ''
            $.each(products, function (i, v) {
                productEl += `
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="product-box" onclick="openProductVariant(${v.id})">
                            <span id="productName_${v.id}">${v.name}</span>
                        </div>
                    </div>
                `
            });
            productsBody.html(productEl)
        })
        .fail(function (xhr,status,error) {
            productsBody.html(`
            <div class="col-12 text-center">
                <p>Barang tidak ditemukan!</p>
            </div>
            `)
        });
    }

    function openProductVariant(id) {
        var productNameText = $('#productName_'+id).text()
        var productNameEl = $('#productName_'+id)

        productNameEl.html('<i class="fas fa-spinner loader"></i>')
        $.get("{{ route('purchase.product-variant') }}", {id})
        .done(function(result){
            productNameEl.html(productNameText)
            $('.modal-page').html(result.content)
        })
        .fail(function (xhr,status,error) {
            productNameEl.html(productNameText)
            Swal.fire('Error',xhr.responseJSON.message,'error')
        });
    }

    function loadProductDetail(){
        var detailContainer = $('.detail-container')
        var detailEl = '';
        var total = 0;
        $.each(dataProducts, function (i, v) {
            var subtotal = v.qty*v.price
            detailEl += `
                <div class="detail">
                    <div class="left">
                        <div class="product">
                            <div class="name">${v.product_name}</div>
                            <div class="variant">${v.product_variant_name ? v.product_variant_name : ''}</div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="price">
                            <div class="stock">${v.qty}x</div>
                            <div class="subtotal">${subtotal.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</div>
                        </div>
                        <div class="aksi" id="aksi_${i}" onclick="hapusDetail(${v.product_variant_id})">
                            <i class="fa fa-trash"></i>
                        </div>
                    </div>
                </div>
            `;
            total += subtotal
        });
        detailContainer.html(detailEl)
        $('#total').html(total.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "."))
    }

    function hapusDetail(id){
        const index = dataProducts.findIndex(item => item.product_variant_id == id);
        if (index !== -1) {
            dataProducts.splice(index, 1);
        }
        loadProductDetail()
    }

    function reset() {
        dataProducts = []
        $('input').val('')
        loadProduct()
        loadProductDetail()
    }

    function formatNumber(el) {
        return el.value = el.value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function sisaBayar(dibayar,total){
        var sisa = total-dibayar;
        // if(sisa >= total || sisa < 0){
        //     sisa = 0;
        // }
        $('#sisa').val(sisa);
        formatNumber($('#sisa')[0])
    }
    $('#dibayar').keyup(function () {
        var dibayar = parseInt(String($(this).val()).replaceAll('.',''))
        var total = ($('#total').text().length > 1) ? parseInt($('#total').text().replaceAll('.','')) : 0
        formatNumber($(this)[0])
        sisaBayar(dibayar,total)
    })


    function simpan(){
        $('.form-action').append(`
        <div class="backdrop-load">
            <h4 style="font-weight: 700; color: #fff;">Proses...</h4>
        </div>
        `)
        var data = {
            vendor_id: $('#vendor_id').val(),
            dibayar: $('#dibayar').val(),
            products: dataProducts
        }

        $.ajax({
            type: "post",
            url: "{{ route('purchase.store-restock') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            data: data,
            dataType: "json",
            success: function (response) {
                $('.form-action .backdrop-load').remove()
                Swal.fire('Success',response.message,'success')
                reset()
            },
            error: function (xhr,status,error) {
                $('.form-action .backdrop-load').remove()
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
@endpush
