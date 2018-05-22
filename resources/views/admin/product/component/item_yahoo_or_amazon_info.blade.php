<div class="box box-success" id="item-yaohoo-or-amazon-content">
    <div class="box-header with-border">@lang('view.product_details')</div>
    <div class="box-body">
        @if($price)
        <p>@lang('view.buy_price'): <span id="buy_price">{{ $price }}</span></p>
        @endif
        <input type="file" name="files" id="files">
    </div>
    <span id="product_size" class="display-none">{{ isset($data['product_size']) ? $data['product_size'] : '' }}</span>
    <span id="commodity_weight" class="display-none">{{ isset($data['commodity_weight']) ? $data['commodity_weight'] : '' }}</span>
    <span id="length" class="display-none">{{ isset($data['length']) ? $data['length'] : '' }}</span>
    <span id="height" class="display-none">{{ isset($data['height']) ? $data['height'] : '' }}</span>
    <span id="width" class="display-none">{{ isset($data['width']) ? $data['width'] : '' }}</span>
</div>
