var token = window.Laravel.csrfToken;
var isChangeItemId = false;
var isChangeEbayOrAmazon = false;

jQuery(document).ready(function() {
    intSelectCategory();
    if ($('#item-yaohoo-or-amazon-content').length) {
        $.get(urlGetImageInit, function(data, status) {
            if (data.status) {
                fnInitFIlerImage(data.images);
            }
        });
    }
    $(document).on("click", "#btn-get-item-ebay-info", function() {
        getItemEbayInfo();
    });

    $(document).on("change", "#item_id", function() {
        if ($('#item-ebay-content').length || isChangeItemId) {
            isChangeItemId = true;
            getItemEbayInfo();
        }
    });

    $(document).on("click", "#add-specific", function() {
        if (numberSpecificItem == 20) {
            $('#div-add-specific').addClass('display-none');
        }
        var specificItemAdd = $('#specific-item-none div');
        specificItemAdd.find('.specific-name').attr('name', "dtb_item_specifics[" + numberSpecificItem + "][name]");
        specificItemAdd.find('.specific-value').attr('name', "dtb_item_specifics[" + numberSpecificItem + "][value]");
        specificItemAdd.find('.error-name span').removeClass();
        specificItemAdd.find('.error-name span').addClass('error-dtb_item_specifics_' + numberSpecificItem + '_name');
        specificItemAdd.find('.error-value span').removeClass();
        specificItemAdd.find('.error-value span').addClass('error-dtb_item_specifics_' + numberSpecificItem + '_value');
        $('#div-add-specific').before($('#specific-item-none').html());
        numberSpecificItem++;
        specificItemAdd.find('.specific-name').removeAttr('name');
        specificItemAdd.find('.specific-value').removeAttr('name');
        if (numberSpecificItem == 20) {
            $('#div-add-specific').addClass('display-none');
        }
        resetSpecificiItemNone();
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
                    $(this).find('.error-name span').removeClass();
                    $(this).find('.error-name span').addClass('error-dtb_item_specifics_' + index + '_name');
                    $(this).find('.error-value span').removeClass();
                    $(this).find('.error-value span').addClass('error-dtb_item_specifics_' + index + '_value');
                }
            });
            resetSpecificiItemNone();
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
        fd.append('dtb_item[category_name]', $( "#category-id option:selected" ).text());
        fd.append('dtb_item[condition_name]', $( "#condition-id option:selected" ).text());
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
                    $('p.error-validate').text('');
                    $('.error-validate-specifics span').text('');
                    $('.error-validate').parent().removeClass('has-error');
                    $('.error-validate-specifics').parent().removeClass('has-error');
                    messageError = data.message_error;
                    jQuery.each(messageError, function(index, value) {
                        $('.error-' + index).text(value);
                        $('span.error-' + index).parent().parent().addClass('has-error');
                        $('p.error-' + index).parent().addClass('has-error');
                    });
                    if (messageError.dtb_item_condition_des
                        || messageError.dtb_item_item_name
                        || messageError.dtb_item_condition_name
                        || messageError.dtb_item_jan_upc
                        ) {
                        $('html, body').animate({
                            scrollTop: $("#item_name").offset().top
                        }, 3000);
                    } else {
                        if (messageError.dtb_item_price) {
                            $('html, body').animate({
                                scrollTop: $("#sell_price").offset().top
                            }, 3000);
                        }
                    }
                }
            },
            complete: function () {
                button.data('requestRunning', false);
                $('body').removeClass('loading-ajax');
            }
        });
    });

    $(document).on("click", "#btn-get-yahoo-or-amazon", function() {
        getYahooOrAmazonInfo($(this), false);
    });

    $(document).on("click", "#btn-calculator-profit",function() {
        getCalculateProfitInfo(true, false);
    });

    // $('.type').on('ifChanged', function(event) {
    // $('.type').change(function() {
    $(document).on("change", "#id_ebay_or_amazon", function() {
        var isShowYaohoo = $('#item-yaohoo-or-amazon-content').length;
        // var isShowCalculate = $('#item-calculator-info').length;
        if (isShowYaohoo || isChangeEbayOrAmazon) {
            isChangeEbayOrAmazon = true;
            if ($('#item-yahoo-or-amazon-invalid').text().length) {
                getYahooOrAmazonInfo($("#btn-get-yahoo-or-amazon"), true);
            } else {
                getYahooOrAmazonInfo($("#btn-get-yahoo-or-amazon"), false);
            }
        }
    });

    $(document).on("change", ".type", function() {
        var isShowYaohoo = $('#item-yaohoo-or-amazon-content').length;
        // var isShowCalculate = $('#item-calculator-info').length;
        if (isShowYaohoo || isChangeEbayOrAmazon) {
            isChangeEbayOrAmazon = true;
            getYahooOrAmazonInfo($("#btn-get-yahoo-or-amazon"), true);
        }
    });

    $(document).on("change", "#category-id, #material-quantity, #setting-shipping, #buy_price, #sell_price, #height, #width, #length", function() {
        if ($('#item-calculator-info').length) {
            getCalculateProfitInfo(true, false);
        }
    });
});

function getItemEbayInfo()
{
    $('body').addClass('loading-ajax');
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
                $('#item-ebay-invalid').addClass('display-none');
                $('#item-ebay-invalid').parent().parent().removeClass('has-error');
                intSelectCategory();
                if ($('#sell_price').length && $('#buy_price').length) {
                    getCalculateProfitInfo(false, false);
                }
            } else {
                // $('#conten-ajax .ebay-info').html('');
                // $('#conten-ajax .calculator-info').html('');
                $('#item-ebay-invalid').removeClass('display-none');
                $('#item-ebay-invalid').parent().parent().addClass('has-error');
            }
            toggleBtnSlove();
        },
        complete: function () {
            $('body').removeClass('loading-ajax');
        }
    });
}
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
function getYahooOrAmazonInfo(button, isChangeType)
{
    $('body').addClass('loading-ajax');
    if (button.data('requestRunning')) {
        return;
    }
    button.data('requestRunning', true);
    var token = window.Laravel.csrfToken;
    var data = {
        _token: token,
        item_id: $('#id_ebay_or_amazon').val(),
        type: $('.type:checked').val(),
    };
    $.ajax({
        url: urlGetItemYahooOrAmazonInfo,
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (data) {
            if (data.status) {
                $('#item-yahoo-or-amazon-invalid').text('');
                $('#item-yahoo-or-amazon-invalid').parent().parent().removeClass('has-error');
                $('#conten-ajax .yahoo-or-amazon-info').html(data.data);
                fnInitFIlerImage(data.image);
                var isShowCalculate = $('#item-calculator-info').length;
                if (isShowCalculate) {
                    getCalculateProfitInfo(false, isChangeType);
                }
                if ($('#item-ebay-content').length) {
                    $('html, body').animate({
                        scrollTop: $("#conten-ajax .yahoo-or-amazon-info").offset().top
                    }, 3000);
                }
            } else {
                // $('#conten-ajax .yahoo-or-amazon-info').html('');
                // $('#conten-ajax .calculator-info').html('');
                $('#item-yahoo-or-amazon-invalid').text(data.message_error);
                $('#item-yahoo-or-amazon-invalid').parent().parent().addClass('has-error');
            }
            toggleBtnSlove();
        },
        complete: function () {
            button.data('requestRunning', false);
            $('body').removeClass('loading-ajax');
        }
    });
}

function getCalculateProfitInfo(isValidate, isChangeType)
{
    $('body').addClass('loading-ajax');
    isShowCalculate = $('#item-calculator-info').length;
    var materialQuantity = $('#material-quantity').val() ? $('#material-quantity').val() : null;
    var buyPrice;
    if (isChangeType) {
        buyPrice = $('#buy_price_span').text();
    } else {
        if (isShowCalculate && $('#buy_price').length) {
            if ($('#buy_price').val()) {
                buyPrice = $('#buy_price').val();
            } else {
                if ($('#error-dtb_item_buy_price').text().length) {
                    buyPrice = $('#buy_price').val();
                } else {
                    buyPrice = $('#buy_price_span').text();
                }
            }
        } else {
            buyPrice = $('#buy_price_span').text();
        }
    }
    var token = window.Laravel.csrfToken;
    var type = $('.type:checked').val();
    var data = {
        _token: token,
        is_validate: isValidate,
        is_update: isShowCalculate,
        material_quantity: materialQuantity,
        type: type,
        height: (isShowCalculate && $('#height').length) ? $('#height').val() : $('#height_hidden').val(),
        width: (isShowCalculate && $('#width').length) ? $('#width').val() : $('#width_hidden').val(),
        length: (isShowCalculate && $('#length').length) ? $('#length').val() : $('#length_hidden').val(),
        commodity_weight: $('#commodity_weight').val(),
        sell_price: $('#sell_price').val(),
        buy_price: buyPrice,
        category_id: $('#category-id').val(),
        ship_fee: (isShowCalculate && $('#ship_fee').length) ?  $('#ship_fee').val() : '',
        setting_shipping: (isShowCalculate && $('#setting-shipping').length) ? $('#setting-shipping').val() : '',
    };
    $.ajax({
        url: urlCalculatorProfit,
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (data) {
            $('#error-material-quantity').text('');
            $('.error-dtb_item_price').text('');
            $('.error-dtb_item_buy_price').text('');
            $('.error-dtb_item_height').text('');
            $('.error-dtb_item_width').text('');
            $('.error-dtb_item_length').text('');
            $('.error-dtb_item_category_id').text('');
            $('.error-dtb_item_category_id').parent().removeClass('has-error');
            $('.error-dtb_item_price').parent().removeClass('has-error');
            $('.error-dtb_item_buy_price').parent().removeClass('has-error');
            $('.error-dtb_item_height').parent().removeClass('has-error');
            $('.error-dtb_item_width').parent().removeClass('has-error');
            $('.error-dtb_item_length').parent().removeClass('has-error');
            $('#error-material-quantity').parent().removeClass('has-error');
            if (data.status) {
                $('#conten-ajax .calculator-info').html(data.data);
            } else {
                messageError = data.message_error;
                if(messageError.material_quantity) {
                    $('#error-material-quantity').text(messageError.material_quantity);
                    $('#error-material-quantity').parent().addClass('has-error');
                }
                if(messageError.buy_price) {
                    $('.error-dtb_item_buy_price').text(messageError.buy_price);
                    $('.error-dtb_item_buy_price').parent().addClass('has-error');
                }
                if(messageError.sell_price) {
                    $('.error-dtb_item_price').text(messageError.sell_price);
                    $('.error-dtb_item_price').parent().addClass('has-error');
                }
                if(messageError.category_id) {
                    $('.error-dtb_item_category_id').text(messageError.category_id);
                    $('.error-dtb_item_category_id').parent().addClass('has-error');
                }
                if(messageError.height) {
                    $('.error-dtb_item_height').text(messageError.height);
                    $('.error-dtb_item_height').parent().addClass('has-error');
                }
                if(messageError.width) {
                    $('.error-dtb_item_width').text(messageError.width);
                    $('.error-dtb_item_width').parent().addClass('has-error');
                }
                if(messageError.length) {
                    $('.error-dtb_item_length').text(messageError.length);
                    $('.error-dtb_item_length').parent().addClass('has-error');
                }
                // // if (!isShowCalculate) {
                //     $('html, body').animate({
                //         scrollTop: $("#sell_price").offset().top
                //     }, 3000);                        
                // // }
            }
            toggleBtnSlove();
        },
        complete: function () {
            $('body').removeClass('loading-ajax');
        }
    });
}

function resetSpecificiItemNone()
{
    var specificItemNone = $('#specific-item-none div');
    specificItemNone.find('.specific-name').removeAttr('name');
    specificItemNone.find('.specific-value').removeAttr('name');
    specificItemNone.find('.error-name span').removeClass();
    specificItemNone.find('.error-value span').removeClass();
}

function intSelectCategory()
{
    $('#category-id').select2({
        ajax: {
            url: urlSearchCategory,
            dataType: 'json',
            data: function(params) {
                var query = {
                    category_path: params.term, limit: 10,
                    page: params.page || 1
                }
                return query;
            },
            delay: 500,
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.results,
                    pagination: {
                        more: (params.page * 10) < data.count_filtered
                    }
                };
            }
        }
    });
}
