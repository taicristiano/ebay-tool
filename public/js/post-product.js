jQuery(document).ready(function() {
    $('#get-item-ebay-info').click(function(event) {
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
                    $('#conten-ajax').append(data.data);
                } else {
                    $('#item-ebay-invalid').removeClass('display-none');
                }
            },
        });
    });
});