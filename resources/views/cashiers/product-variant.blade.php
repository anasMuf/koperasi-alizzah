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
                    @foreach ($product->product_variants as $product_variant)
                        @if($product_variant->name)
                            <div class="variant-choise">
                                <input type="radio" name="product_variant" id="product_variant_{{ $product_variant->id }}" value="{{ $product_variant->id }}|{{ $product_variant->price }}|{{ $product_variant->name }}" required>
                                <label for="product_variant_{{ $product_variant->id }}">{{ $product_variant->name }} <small>sisa:{{ $product_variant->stock }}</small></label>
                            </div>
                        @else
                            <small>sisa:{{ $product_variant->stock }}</small>
                            <input type="hidden" name="product_variant" id="product_variant_{{ $product_variant->id }}" value="{{ $product_variant->id }}|{{ $product_variant->price }}">
                        @endif
                    @endforeach
                    <input type="number" name="qty" id="qty" value="1" min="1" class="form-control" required>
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
                formattedData['price'] = parseInt(productVariant[1]);
                formattedData['product_variant_name'] = productVariant.length == 3 ? productVariant[2] : null;
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
