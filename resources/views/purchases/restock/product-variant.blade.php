<div class="modal fade" id="modalProductVariant" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $product->name }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addVariant">
                    <input type="hidden" name="product_name" value="{{ $product->name }}">
                    @php
                        $purchase_price = 0;
                    @endphp
                    @foreach ($product->product_variants as $product_variant)
                        @if($product_variant->name)
                            <div class="variant-choise">
                                <input type="radio" name="product_variant" id="product_variant_{{ $product_variant->id }}" value="{{ $product_variant->id }}|{{ $product_variant->purchase_price }}|{{ $product_variant->name }}" required>
                                <label for="product_variant_{{ $product_variant->id }}">{{ $product_variant->name }} <small>sisa:{{ $product_variant->stock }}</small></label>
                            </div>
                        @else
                            @php
                                $purchase_price = $product_variant->purchase_price;
                            @endphp
                            <small>sisa: {{ $product_variant->stock }}</small>
                            <input type="hidden" name="product_variant" id="product_variant_{{ $product_variant->id }}" value="{{ $product_variant->id }}">
                        @endif
                    @endforeach
                    <input type="number" name="qty" id="qty" value="1" min="1" class="form-control" required>
                    <small id="previousPrice">Harga sebelumnya: Rp {{ number_format($purchase_price,0,',','.') }}</small>
                    <input type="text" name="price" id="price" class="form-control" placeholder="Tulis Harga Beli">
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tupup</button>
                <input type="submit" class="btn btn-primary" form="addVariant" value="Tambahkan">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $('#modalProductVariant').modal('show')

    $('.variant-choise input[name=product_variant]').change(function(){ // bind a function to the change event
        if( $(this).is(":checked") ){ // check if the radio is checked
            var val = $(this).val(); // retrieve the value
            var productVariant = val.split('|')
            var price = parseInt(productVariant[1])
            $('#previousPrice').html(`Harga sebelumnya: Rp ${price.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`)
        }
    });

    $('#addVariant').submit(function(e){
        e.preventDefault()
        var data = $(this).serializeArray()
        var formattedData = {};

        data.forEach(function(item) {
            if (item['name'] === 'product_name') {
                formattedData['product_name'] = item.value;
            } else if (item['name'] === 'product_variant') {
                var productVariant = item.value.split('|')
                formattedData['product_variant_id'] = productVariant[0];
                formattedData['product_variant_name'] = productVariant.length == 2 ? productVariant[1] : null;
            } else if (item['name'] === 'price') {
                formattedData['price'] = parseInt(item.value);
            } else if (item['name'] === 'qty') {
                formattedData['qty'] = parseInt(item.value);
            }
        });

        var existingProduct = dataProducts.find(item => item.product_variant_id === formattedData.product_variant_id)
        if(existingProduct){
            existingProduct.qty += formattedData.qty
        }else{
            dataProducts.push(formattedData)
        }

        loadProductDetail()
        $('#modalProductVariant').modal('hide')
    })
</script>
