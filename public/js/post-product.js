jQuery(document).ready(function() {
    var token = window.Laravel.csrfToken;
    if ($('#item-yaohoo-or-amazon-content').length) {
        $.get(urlGetImageInit, function(data, status) {
            if (data.status) {
                fnInitFIlerImage(data.images);
            }
        });
    }
    $(document).on("click", "#btn-get-item-ebay-info", function() {
        $('body').addClass('loading-ajax');
        var button = $(this)
        if (button.data('requestRunning')) {
            return;
        }
        button.data('requestRunning', true);
        var data = {
            _token: token,
            item_id: $('#item_id').val(),
        };
        $.ajax({
            url: urlGetItemEbayInfo,
            type: 'post',
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.status) {
                    $('#conten-ajax .ebay-info').html(data.data);
                    numberSpecificItem = $('.specific-item').length - 1;
                } else {
                    $('#conten-ajax .ebay-info').html('');
                    $('#conten-ajax .calculator-info').html('');
                    $('#item-ebay-invalid').removeClass('display-none');
                }
                toggleBtnSlove();
            },
            complete: function () {
                button.data('requestRunning', false);
                $('body').removeClass('loading-ajax');
            }
        });
    });

    $(document).on("click", "#add-specific", function() {
        if (numberSpecificItem == 20) {
            $('#div-add-specific').addClass('display-none');
        }
        var specificItemAdd = $('#specific-item-none .specific-item');
        specificItemAdd.find('.specific-name').attr('name', "dtb_item_specifics[" + numberSpecificItem + "][name]");
        specificItemAdd.find('.specific-value').attr('name', "dtb_item_specifics[" + numberSpecificItem + "][value]");
        $('#div-add-specific').before($('#specific-item-none').html());
        numberSpecificItem++;
        specificItemAdd.find('.specific-name').removeAttr('name');
        specificItemAdd.find('.specific-value').removeAttr('name');
        if (numberSpecificItem == 20) {
            $('#div-add-specific').addClass('display-none');
        }
    });

    $(document).on("click", ".delete-specific", function() {
        if (confirm('Are you sure ?')) {
            $(this).parent().parent().parent().parent().remove();
            numberSpecificItem--;
            if (numberSpecificItem < 20) {
                $('#div-add-specific').removeClass('display-none');
            }
            $('.specific-item').each(function(index) {
                if (index < numberSpecificItem) {
                    $(this).find('.specific-name').attr('name', "dtb_item_specifics[" + index + "][name]");
                    $(this).find('.specific-value').attr('name', "dtb_item_specifics[" + index + "][value]");
                }
            });
        }
    });

    $(document).on("click", "#save", function() {
        $('body').addClass('loading-ajax');
        var button = $(this)
        if (button.data('requestRunning')) {
            return;
        }
        button.data('requestRunning', true);
        var fd = new FormData($('#form-post')[0]);
        var error = false;
        fd.append('_token', token);
        var api = $.fileuploader.getInstance('#files');
        var files = [];
        var fileUpload = api.getFiles();
        fd.append('dtb_item[original_id]', $('#id_ebay_or_amazon').val());
        fd.append('dtb_item[item_id]', $('#item_id').val());
        fd.append('dtb_item[type]', $('.type:checked').val());
        $.each(fileUpload, function (index, value) {
            fd.append('files_upload_' + index, value.file);
        });
        fd.append('number_file', fileUpload.length);
        $.ajax({
            url: urlPostProductConfirm,
            type: 'post',
            dataType: 'json',
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status) {
                    window.location.href = data.url;
                } else {
                    $('#item-ebay-invalid').removeClass('display-none');
                }
            },
            complete: function () {
                button.data('requestRunning', false);
                $('body').removeClass('loading-ajax');
            }
        });
    });

    $(document).on("click", "#btn-get-yahoo-or-amazon", function() {
        getYahooOrAmazonInfo($(this))
    });

    $(document).on("click", "#btn-calculator-profit",function() {
        getCalculateProfitInfo($(this));
    });

    $('.type').on('ifChanged', function(event){
        var isShowYaohoo = $('#item-yaohoo-or-amazon-content').length;
        var isShowCalculate = $('#item-calculator-info').length;
        if (isShowYaohoo) {
            getYahooOrAmazonInfo($("#btn-get-yahoo-or-amazon"));
        }
    });

    $(document).on("change", "#material-quantity, #setting-shipping", function() {
        var materialQuantity = $('#material-quantity').val() ? $('#material-quantity').val() : 0;
        $('body').addClass('loading-ajax');
        var data = {
            _token: token,
            material_quantity: materialQuantity,
            setting_shipping: $('#setting-shipping').val(),
            commodity_weight: $('#commodity-weight').val(),
            sell_price: $('#sell_price').val(),
            buy_price: $('#buy_price').text(),
            paypal_fee: $('#paypal-fee').val(),
            ebay_fee: $('#ebay-fee').val(),
        };
        $.ajax({
            url: updateProfit,
            type: 'post',
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.status) {
                    $('#error-material-quantity').addClass('display-none');
                    $('#error-material-quantity').text('');
                    $('#profit').val(data.profit);
                    $('#ship_fee').val(data.ship_fee);
                } else {
                    $('#error-material-quantity').text(data.message_error.material_quantity);
                    $('#error-material-quantity').removeClass('display-none');
                }
            },
            complete: function () {
                $('body').removeClass('loading-ajax');
            }
        });
    });
});

function toggleBtnSlove()
{
    var isShowEbay = $('#item-ebay-content').length;
    var isShowYaohoo = $('#item-yaohoo-or-amazon-content').length;
    var isShowCalculate = $('#item-calculator-info').length;
    if (isShowEbay && isShowYaohoo) {
        $('#profit-calculation').removeClass('display-none');
        if (isShowCalculate) {
            $('#post-product').removeClass('display-none');
        } else {
            $('#post-product').addClass('display-none');
        }
    } else {
        $('#post-product').addClass('display-none');
        $('#profit-calculation').addClass('display-none');
    }
}

function urlEncode(str)
{
    str = encodeURIComponent(str);
    str = str.replace(/\*/g, '%2A');
    str = str.replace(/\(/g, '%28');
    str = str.replace(/\)/g, '%29');
    str = str.replace(/'/g, '%27');
    str = str.replace(/\!/g, '%21');
    return str;
}
function getYahooOrAmazonInfo(button)
{
    $('body').addClass('loading-ajax');
    if (button.data('requestRunning')) {
        return;
    }
    // var timestamp = dateFormat(new Date(), 'isoUtcDateTime');
    // timestamp = urlEncode(timestamp);
    button.data('requestRunning', true);

    var endpoint = "mws.amazonservices.jp";
    var apiPath = '/Products/2011-10-01';
    var stringToSign = "POST\n" + endpoint + "\n";
    var timestamp = dateFormat(new Date(), 'isoUtcDateTime');
    timestamp = urlEncode(timestamp);
    stringToSign += apiPath + "\n";
    stringToSign += 'AWSAccessKeyId=AKIAJWROE4YTDKN5COQQ&Action=ListMatchingProducts&MWSAuthToken=amzn.mws.f8b1b1e5-f8df-3d8c-48ff-d8655ad92d86&MarketplaceId=A1VC38T7YXB528&Query=0439708184&SellerId=A2GI94OS9KGZVF&SignatureMethod=HmacSHA256&SignatureVersion=2&Timestamp='+timestamp+'&Version=2011-10-01';
    // var stringToSign = 'https://mws.amazonservices.jp/Products/2011-10-01';
    var secretKey = 'l4CCqytm56ps5QFw7AFv347bKxqzJWK4xL2hrVmb';
    console.log(stringToSign);
    var hmac = Crypto.HMAC(Crypto.SHA256, stringToSign, secretKey, { asString: false });

    var b64hmac = hexstr2b64(hmac);
    var b64hmac = urlEncode(b64hmac);
    var j = b64hmac.length % 4;
    for(var i=0;i<j;i++) b64hmac += '=';

    var token = window.Laravel.csrfToken;
    var data = {
        _token: token,
        item_id: $('#id_ebay_or_amazon').val(),
        type: $('.type:checked').val(),
        timestamp: timestamp,
        sign: urlEncode(b64hmac),
    };
    $.ajax({
        url: urlGetItemYahooOrAmazonInfo,
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (data) {
            if (data.status) {
                $('#conten-ajax .yahoo-or-amazon-info').html(data.data);
                fnInitFIlerImage(data.image);
                var isShowCalculate = $('#item-calculator-info').length;
                if (isShowCalculate) {
                    getCalculateProfitInfo($('#btn-calculator-profit'));
                }
                if ($('#item-ebay-content').length) {
                    $('html, body').animate({
                        scrollTop: $("#conten-ajax .yahoo-or-amazon-info").offset().top
                    }, 3000);
                }
            } else {
                $('#conten-ajax .yahoo-or-amazon-info').html('');
                $('#conten-ajax .calculator-info').html('');
                $('#item-ebay-invalid').removeClass('display-none');
            }
            toggleBtnSlove();
        },
        complete: function () {
            button.data('requestRunning', false);
            $('body').removeClass('loading-ajax');
        }
    });
}

function getCalculateProfitInfo(button)
{
    $('body').addClass('loading-ajax');
    if (button.data('requestRunning')) {
        return;
    }
    button.data('requestRunning', true);
    var token = window.Laravel.csrfToken;
    var type = $('.type:checked').val();
    var data = {
        _token: token,
        type: type,
        product_size: $('#product_size').val(),
        commodity_weight: $('#commodity_weight').val(),
        length: $('#length').val(),
        height: $('#height').val(),
        width: $('#width').val(),
        sell_price: $('#sell_price').val(),
        buy_price: $('#buy_price').text(),
        category_id: $('#category_id').val(),
    };
    $.ajax({
        url: urlCalculatorProfit,
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (data) {
            if (data.status) {
                $('#conten-ajax .calculator-info').html(data.data);
            } else {
                $('#conten-ajax .calculator-info').html('');
            }
            toggleBtnSlove();
        },
        complete: function () {
            button.data('requestRunning', false);
            $('body').removeClass('loading-ajax');
        }
    });
}