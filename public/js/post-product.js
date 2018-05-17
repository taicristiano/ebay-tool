jQuery(document).ready(function() {
    numberSpecificItem = 0;
    var token = window.Laravel.csrfToken;
    $(document).on("click", "#btn-get-item-ebay-info",function() {
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
        });
    });

    $(document).on("click", "#add-specific",function() {
        if (numberSpecificItem == 20) {
            $('#div-add-specific').addClass('display-none');
        }
        var specificItemAdd = $('#specific-item-none .specific-item');
        specificItemAdd.find('.specific-name').attr('name', "dtb_item_specifics[" + numberSpecificItem + "]['name']");
        specificItemAdd.find('.specific-value').attr('name', "dtb_item_specifics[" + numberSpecificItem + "]['value']");
        $('#div-add-specific').before($('#specific-item-none').html());
        numberSpecificItem++;
        specificItemAdd.find('.specific-name').removeAttr('name');
        specificItemAdd.find('.specific-value').removeAttr('name');
        if (numberSpecificItem == 20) {
            $('#div-add-specific').addClass('display-none');
        }
    });

    $(document).on("click", ".delete-specific",function() {
        if (confirm('Are you sure ?')) {
            $(this).parent().parent().parent().parent().remove();
            numberSpecificItem--;
            if (numberSpecificItem < 20) {
                $('#div-add-specific').removeClass('display-none');
            }
            $('.specific-item').each(function(index) {
                if (index < numberSpecificItem) {
                    $(this).find('.specific-name').attr('name', "dtb_item_specifics[" + index + "]['name']");
                    $(this).find('.specific-value').attr('name', "dtb_item_specifics[" + index + "]['value']");
                }
            });
        }
    });

    $(document).on("click", "#save", function() {
        var fd = new FormData($('#form-post')[0]);
        var error = false;
        fd.append('_token', token);
        var data = $('#form-post').serializeArray();
        var api = $.fileuploader.getInstance('#files');
        var files = [];
        var fileUpload = api.getFiles();
        $.each(fileUpload, function (index, value) {
            // files.push(value.file);
            fd.append('files_upload_' + index, value.file);
            // data.push({name: 'files_upload_' + index, value: value.file});
        });
        console.log(fd);
        fd.append('number_file', fileUpload.length);
        data.push({name: 'number_file', value: fileUpload.length});
        // data.files_upload = files;
        console.log(data);
        $.ajax({
            url: urlPosProduct,
            type: 'post',
            dataType: 'json',
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status) {
                    // $('#conten-ajax').append(data.data);
                    $('#conten-ajax').html(data.data);
                    numberSpecificItem = $('.specific-item').length - 1;
                } else {
                    $('#conten-ajax').html('');
                    $('#item-ebay-invalid').removeClass('display-none');
                }
            },
        });
    });

    $(document).on("click", "#btn-get-ebay-or-amazon",function() {
        var token = window.Laravel.csrfToken;
        var data = {
            _token: token,
            item_id: $('#id_ebay_or_amazon').val(),
            type: $('.type').val(),
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
                } else {
                    $('#conten-ajax .yahoo-or-amazon-info').html('');
                    $('#conten-ajax .calculator-info').html('');
                    $('#item-ebay-invalid').removeClass('display-none');
                }
                toggleBtnSlove();
            },
        });
    });

    $(document).on("click", "#btn-calculator-profit",function() {
        var token = window.Laravel.csrfToken;
        var data = {
            _token: token,
            type: $('.type').val(),
        };
        $.ajax({
            url: urlCalculatorProfit,
            type: 'post',
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.status) {
                    $('#conten-ajax .calculator-info').html(data.data);
                    // fnInitFIlerImage(data.image);
                    // toggleBtnSlove();
                } else {
                    $('#conten-ajax .calculator-info').html('');
                    // $('#item-ebay-invalid').removeClass('display-none');
                }
                toggleBtnSlove();
            },
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