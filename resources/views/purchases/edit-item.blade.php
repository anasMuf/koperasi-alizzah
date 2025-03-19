@extends('layouts.main')

@push('append-styles')
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
@endpush

@section('contents')
<div class="row preload-page mt-3" style="display: none;">
    <div class="col-xl-12 d-flex justify-content-center">
        <img src="{{ asset('assets/dist/img/preload.gif') }}" alt="">
    </div>
</div>
<div class="main-page">
    <div class="row">
        <form class="formData" style="display: contents;">
            @csrf
            <input type="hidden" name="product_id" value="{{ $data->purchase_details[0]->product_variant->product->id }}">

            <input type="hidden" name="purchase_id" value="{{ $data->id }}">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Barang</h3>
                    </div>
                    <div class="card-body data-product">
                        <div class="form-group"
                        >
                            <label for="name_product">Nama Barang</label>
                            <input type="text" name="name_product" id="name_product"
                            class="form-control" placeholder="Masukkan Nama Barang" value="{{ $data->purchase_details[0]->product_variant->product->name }}">
                        </div>
                        <div class="form-group">
                            <label for="category_id">Kategori Barang</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">.:: Pilih Kategori Barang ::.</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $data && $data->purchase_details[0]->product_variant->product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group d-none">
                            <div class="is_variant mr-5">
                                <input type="checkbox" name="is_variant" id="is_variant" @checked($is_variant)>
                                <label for="is_variant">Tambahkan varian barang</label>
                            </div>
                        </div>
                        <div class="product_variant_container" @if(!$is_variant) style="display: none;" @endif>
                            @php
                                $subtotals = [];
                            @endphp
                            @foreach ($data->purchase_details as $key => $purchase_detail)
                            <div class="product_variant row" data-variant="{{ $key }}">
                                <input type="hidden" name="purchase_detail_id[]" id="purchase_detail_id_{{ $key }}" value="{{ $purchase_detail->id }}">
                                <input type="hidden" name="product_variant_id[]" id="product_variant_id_{{ $key }}" value="{{ $purchase_detail->product_variant_id }}">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name_product_variant">Nama Varian Barang</label>
                                                <input type="text" name="name_product_variant[]" id="name_product_variant_{{ $key }}" class="form-control" data-variant="{{ $key }}" value="{{ $purchase_detail->product_variant->name }}" placeholder="Masukkan Nama Barang Variant" @disabled(!$is_variant)>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="stock">Stok</label>
                                                <input type="number" name="stock[]" id="stock_{{ $key }}" min="{{ $key }}" class="form-control" data-variant="{{ $key }}" value="{{ $purchase_detail->qty }}" placeholder="Masukkan Stok" @disabled(!$is_variant)>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="price">Harga Beli Satuan</label>
                                                <input type="text" name="price[]" id="price_{{ $key }}" class="form-control" data-variant="{{ $key }}" onkeyup="formatNumber(this)" value="{{ number_format($purchase_detail->purchase_price,0,',','.') }}" placeholder="Masukkan Harga Beli Satuan" @disabled(!$is_variant)>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $subtotals[] = $purchase_detail->subtotal;
                            @endphp
                            @endforeach
                            @php
                                $strSubtotals = implode(',',$subtotals);
                            @endphp
                        </div>
                        <div class="product" @if($is_variant) style="display: none;" @endif>
                            <input type="hidden" name="product_variant_id" value="{{ $data->purchase_details[0]->product_variant_id }}" @disabled($is_variant)>

                            <input type="hidden" name="purchase_detail_id" value="{{ $data->purchase_details[0]->id }}" @disabled($is_variant)>

                            <input type="hidden" name="invoice" value="{{ $data->invoice }}">

                            <div class="form-group">
                                <label for="stock">Stok</label>
                                <input type="number" name="stock" id="stock" min="0" class="form-control" placeholder="Masukkan Stok" value="{{ $data->purchase_details[0]->qty }}" @disabled($is_variant)>
                            </div>
                            <div class="form-group">
                                <label for="price">Harga Beli Satuan</label>
                                <input type="text" name="price" id="price" class="form-control" onkeyup="formatNumber(this)" placeholder="Masukkan Harga Beli Satuan" value="{{ number_format($data->purchase_details[0]->purchase_price,0,',','.') }}" @disabled($is_variant)>
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
                            <label for="purchase_at">Tanggal Pembelian</label>
                            <input type="date" name="purchase_at" id="purchase_at" class="form-control" value="{{ date('Y-m-d',strtotime($data->purchase_at)) }}">
                        </div>
                        <div class="form-group">
                            <label for="transaction_category_id">Kategori Transaksi</label>
                            <select name="transaction_category_id" id="transaction_category_id" class="form-control" required>
                                <option value="">.:: Pilih Kategori Transaksi ::.</option>
                                @foreach ($transaction_categories as $transaction_category)
                                    <option value="{{ $transaction_category->id }}" {{ $data && $data->transaction_category_id == $transaction_category->id ? 'selected' : '' }}>{{ $transaction_category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vendor_id">Vendor</label>
                            <select name="vendor_id" id="vendor_id" class="form-control" required>
                                <option value="">.:: Pilih Vendor ::.</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ ($vendor->id == $data->vendor_id) ? 'selected' : '' }}>{{ $vendor->name }}</option>
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
                                    <input type="text" name="terbayar" id="terbayar" class="form-control form-transparent" value="{{ number_format($data->terbayar,0,',','.') }}">
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
                        <a href="{{ ($from === 'jurnal') ? route('jurnal.main') : route('purchase.main') }}" class="btn btn-secondary">Kembali</a>
                        <button type="button" class="btn btn-success" onclick="simpan($('.formData'))">Update</button>
                        <button type="button" class="btn btn-danger" onclick="deleteData(`{{ $data->purchase_details[0]->product_variant->product->id }}`)">Hapus</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="other-page"></div>
<div class="modal-page"></div>
@endsection

@push('append-scripts')
<script>
    var strSubtotals = "{{ $strSubtotals }}";
    var subtotals = strSubtotals.split(',').map(Number);
    var from_page = null;

    @if (!$is_variant)
        noVariantEvent()
        $('#is_variant').change(function () {
            if(this.checked){
                $('.product_variant_container').show()
                $('.product_variant_container input, .product_variant_container button').removeAttr('disabled')
                $('.product').hide()
                $('.product input').prop('disabled',true)
                isVariantEvent()
            }else{
                $('.product_variant_container').hide()
                $('.product_variant_container input, .product_variant_container button').prop('disabled',true)
                $('.product').show()
                $('.product input').removeAttr('disabled')
            }
        })
    @else
        isVariantEvent()
        amountPurchaseVariant(subtotals);
    @endif

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
        isVariantEvent()
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

    // not_variant
    function countNoVariant(){
        var stock = parseInt($('.product #stock').val())
        var price = parseInt($('.product #price').val().replaceAll('.','')) //($('.product #price').val().length > 1) ? parseInt($('.product #price').val().replaceAll('.','')) : 0
        amountPurchases(stock,price)
    }
    function noVariantEvent() {
        if($('.product #price').val() !== '' && $('.product #stock').val() !== ''){
            countNoVariant()
        }

        $('.product #stock').on({
            keyup: function(){
                countNoVariant()
            },
            change: function(){
                countNoVariant()
            }
        })

        $('.product #price').keyup(function () {
            var stock = parseInt($('.product #stock').val()) //($('.product #stock').val().length > 1) ? parseInt($('.product #stock').val()) : 0
            var price = parseInt($(this).val().replaceAll('.',''))
            amountPurchases(stock,price)
        })
    }
    function amountPurchases(stock,price,isVariant=false){
        var amount = stock*price;
        if(isVariant){
            return amount;
        }
        $('#amount').val(amount)
        formatNumber($('#amount')[0])
    }

    // is_variant
    function isVariantEvent() {
        $.each($('.product_variant input[name="stock[]"]'), function (i, el) {
            $(el).change(function () {
                var stockVariant = parseInt($(this).val());
                var priceVariant = parseInt($('.product_variant input[name="price[]"]')[i].value.replaceAll('.',''));
                var amountVariant = amountPurchases(stockVariant,priceVariant,true);
                if(amountVariant>0){
                    subtotals[i] = amountVariant;
                }
                amountPurchaseVariant(subtotals);
            })
        });
        $.each($('.product_variant input[name="price[]"]'), function (i, el) {
            $(el).change(function () {
                var stockVariant = parseInt($('.product_variant input[name="stock[]"]')[i].value);
                var priceVariant = parseInt($(this).val().replaceAll('.',''));
                var amountVariant = amountPurchases(stockVariant,priceVariant,true);
                if(amountVariant>0){
                    subtotals[i] = amountVariant;
                }
                amountPurchaseVariant(subtotals);
            })
        });
    }
    function amountPurchaseVariant(subtotals){
        var sumAmount = 0
        $.each(subtotals, function (i, v) {
            sumAmount += v;
        });
        $('#amount').val(sumAmount)
        formatNumber($('#amount')[0])
    }

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

    function simpan(formData){
        const data = new FormData(formData[0]);
        $.ajax({
            type: "post",
            url: "{{ route('purchase.update') }}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success){
                    Swal.fire('Success',response.message,'success')
                    .then((result) => {
                        if(result.isConfirmed){
                            window.location.href =  "{{ route('purchase.main') }}"
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

    function deleteData(id){
        Swal.fire({
            title: "Data akan dihapus!",
            text: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak',
            confirmButtonColor: "#d33",
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ route('purchase.delete') }}",
                    data: {
                        id,
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.success) {
                            Swal.fire('Success',result.message,'success')
                            .then((result) => {
                                if(result.isConfirmed){
                                    window.location.href =  "{{ route('purchase.main') }}"
                                }
                            })
                        }
                        return Swal.fire("Gagal", "Data gagal dihapus", "warning");
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error','Terjadi Kesalahan!','error')
                    }
                });
            }
        });
    }
</script>
@endpush
