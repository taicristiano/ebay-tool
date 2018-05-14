jQuery(document).ready(function() {
    $(document).on("click", "#get-item-ebay-info",function() {
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
                } else {
                    $('#conten-ajax').html('');
                    $('#item-ebay-invalid').removeClass('display-none');
                }
            },
        });
    });

    $(document).on("click", "#add-specific",function() {
        console.log($('#specific-item-none').html());
        $('#div-add-specific').before($('#specific-item-none').html());
    });

    $(document).on("click", ".delete-specific",function() {
        if (confirm('Are you sure ?')) {
            console.log($(this).parents('.specific-item'));
            $(this).parent().parent().parent().parent().remove();
        }
    });
});