<div class="box box-success" id="item-yaohoo-or-amazon-content">
    <div class="box-header with-border">@lang('view.product_image_details')</div>
    <div class="box-body">
        @if (!empty($dataResult['dtb_item']['buy_price']))
        <p>
            @lang('view.buy_price'): <span id="buy_price_span">{{ $dataResult['dtb_item']['buy_price'] }}</span> @lang('view.man')
        </p>
        @else
        <span id="buy_price_span" class="display-none">0</span>
        @endif
        <input type="file" name="files" id="files">
    </div>
    <input id="commodity_weight_hidden" type="hidden" value="{{ isset($dataResult['dtb_item']['commodity_weight']) ? $dataResult['dtb_item']['commodity_weight'] : '' }}">
    <input id="length_hidden" type="hidden" value="{{ isset($dataResult['dtb_item']['length']) ? $dataResult['dtb_item']['length'] : '' }}">
    <input id="height_hidden" type="hidden" value="{{ isset($dataResult['dtb_item']['height']) ? $dataResult['dtb_item']['height'] : '' }}">
    <input id="width_hidden" type="hidden" value="{{ isset($dataResult['dtb_item']['width']) ? $dataResult['dtb_item']['width'] : '' }}">
</div>
