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
                <div class="student">
                    <div class="form-group">
                        <label for="nama_siswa">Nama Siswa</label>
                        <input type="search" name="nama_siswa" id="nama_siswa" class="form-control" placeholder="Tulis Nama Siswa">
                    </div>
                </div>
                <div class="details">
                    <h5>Detail Barang</h5>
                    <div class="detail-container">
                        <div class="detail">
                            <div class="left">
                                <div class="product">
                                    <div class="name">barang 1</div>
                                    <div class="variant">vv</div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price">
                                    <div class="stock">2x</div>
                                    <div class="subtotal">100.000</div>
                                </div>
                                <div class="aksi">
                                    <i class="fa fa-trash"></i>
                                </div>
                            </div>
                        </div>
                        <div class="detail">
                            <div class="left">
                                <div class="product">
                                    <div class="name">barang 1</div>
                                    <div class="variant">vv</div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price">
                                    <div class="stock">2x</div>
                                    <div class="subtotal">100.000</div>
                                </div>
                                <div class="aksi">
                                    <i class="fa fa-trash"></i>
                                </div>
                            </div>
                        </div>
                        <div class="detail">
                            <div class="left">
                                <div class="product">
                                    <div class="name">barang 1</div>
                                    <div class="variant">vv</div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price">
                                    <div class="stock">2x</div>
                                    <div class="subtotal">100.000</div>
                                </div>
                                <div class="aksi">
                                    <i class="fa fa-trash"></i>
                                </div>
                            </div>
                        </div>
                        <div class="detail">
                            <div class="left">
                                <div class="product">
                                    <div class="name">barang 1</div>
                                    <div class="variant">vv</div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price">
                                    <div class="stock">2x</div>
                                    <div class="subtotal">100.000</div>
                                </div>
                                <div class="aksi">
                                    <i class="fa fa-trash"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="amount">
                    <h5>Total</h5>
                    <div class="total">
                        <h3>Rp</h3>
                        <h3><span id="total">1.000.000</span></h3>
                    </div>
                    <div class="form-group">
                        <label for="dibayar">Dibayar</label>
                        <input type="text" name="dibayar" id="dibayar" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="sisa">Sisa</label>
                        <input type="text" name="sisa" id="sisa" class="form-control">
                    </div>
                </div>
                <div class="form-action">
                    <button type="button" class="btn btn-default">Batal</button>
                    <button type="button" class="btn btn-success">Simpan</button>
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
        $.get("{{ route('product.products') }}", {search_product})
        .done(function(result){
            var products = result.data
            var productEl = ''
            $.each(products, function (i, v) {
                productEl += `
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="product-box" onclick="openVariantProduct(${v.id})">
                            ${v.name}
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

    function openVariantProduct(id) {
        $.get("{{ route('product.product') }}", {id})
        .done(function(result){
            console.log(result);
            $('.modal-page').html(result)
        })
        .fail(function (xhr,status,error) {
            Swal.fire('Error',xhr.responseJSON.message,'error')
        });
    }
</script>
@endpush
