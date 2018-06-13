<div class="box box-success" id="item-yaohoo-or-amazon-content">
    <div class="box-header with-border">@lang('view.product_details')</div>
    <div class="box-body">
        @if (!empty($data['dtb_item']['buy_price']))
        <p>
            @lang('view.buy_price'): <span id="buy_price_span">{{ $data['dtb_item']['buy_price'] }}</span> @lang('view.man')
        </p>
        @else
        <span id="buy_price_span" class="display-none">0</span>
        @endif
        <input type="file" name="files" id="files">
    </div>
    <input id="commodity_weight" type="hidden" name="dtb_item[commodity_weight]" value="{{ isset($data['dtb_item']['commodity_weight']) ? $data['dtb_item']['commodity_weight'] : '' }}">
    <input id="length_hidden" type="hidden" name="dtb_item[length]" value="{{ isset($data['dtb_item']['length']) ? $data['dtb_item']['length'] : '' }}">
    <input id="height_hidden" type="hidden" name="dtb_item[height]" value="{{ isset($data['dtb_item']['height']) ? $data['dtb_item']['height'] : '' }}">
    <input id="width_hidden" type="hidden" name="dtb_item[width]" value="{{ isset($data['dtb_item']['width']) ? $data['dtb_item']['width'] : '' }}">
</div>
