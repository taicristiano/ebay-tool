jQuery(document).ready(function() {
    numberSpecificItem = 0;
    $(document).on("click", "#btn-get-item-ebay-info",function() {
        var token = window.Laravel.csrfToken;
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
            console.log($(this).parents('.specific-item'));
            $(this).parent().parent().parent().parent().remove();
            numberSpecificItem--;
            if (numberSpecificItem < 20) {
                $('#div-add-specific').removeClass('display-none');
            }
            $('.specific-item').each(function(index) {
                console.log(index);
                if (index < numberSpecificItem) {
                    $(this).find('.specific-name').attr('name', "dtb_item_specifics[" + index + "]['name']");
                    $(this).find('.specific-value').attr('name', "dtb_item_specifics[" + index + "]['value']");
                }
            });
        }
    });

    $(document).on("click", "#save",function() {
        var data = $('#form-post').serialize();
        $.ajax({
            url: urlPosProduct,
            type: 'post',
            dataType: 'json',
            data: data,
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
});