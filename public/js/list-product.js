jQuery(document).ready(function() {
    $('#end-item-all').on('ifClicked', function(event) {
        $('.end-item').iCheck('toggle');
        // console.log(this.checked);
        // if (this.checked) {
        //     console.log(111);
        //     $('.end-item').iCheck('uncheck');
        // } else {
        //     console.log(222);
        //     $('.end-item').iCheck('check');
        // }
    // $(document).on("click", "#end-item-all", function() {
        $('.end-item').not(this).prop('checked', this.checked);
    });
    $(document).on("click", "#end-item-btn", function() {
        var endItemIds = $("#list-product-table input:checkbox:checked").map(function(){
            return $(this).val();
        }).get();
        if (!endItemIds.length) {
            swal('You must select item!');
        } else {
            swal("Are your sure end item ?", {
                buttons: {
                    cancel: "Cancel",
                    catch: {
                      text: "Ok",
                      value: "ok",
                    },
                },
                icon: "warning",
            })
            .then((value) => {
                switch (value) {
                    case "ok":
                        endItem(endItemIds);
                        break;
                }
            });
        }
    });

    $('#csv-product').click(function(event) {
        event.preventDefault();
        $("#export-csv").attr('action', encodeURI(urlDownloadCsv));
        $('#export-csv').submit();
    });

    $('.keyword').on('keydown', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            updateKeyword($(this));
        }
    });

    $(".keyword").keyup(function(e) {
        var id          = $(this).data('id');
        var keyword     = $(this).val();
        keyword         = keyword.replace(/ /g, "+");
        var urlTemplate = $('#url-ebay-keyword-template').text();
        urlTemplate     = urlTemplate.replace('KEYWORD', keyword);
        $('#ebay_keyword_' + id).attr('href', urlTemplate);
    });
    $(document).on("change", ".keyword", function() {
        updateKeyword($(this));
    });

    $(document).on("click", ".swal-button--confirm", function() {
        window.location.href = urlListProduct;
    })
});

function endItem(itemIds)
{
    var token   = window.Laravel.csrfToken;
    var data    = {
        _token: token,
        item_ids: itemIds,
    };
    $('body').addClass('loading-ajax');
    $.ajax({
        url: urlEndItem,
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (data) {
            if (data.status) {
                swal("", "End product success!", "success");
            } else {
                swal("", "End product error!", "error");
            }
        },
        complete: function () {
            $('body').removeClass('loading-ajax');
        }
    });
}

function updateKeyword(item)
{
    var keyword = item.val();
    var id      = item.data('id');
    var token   = window.Laravel.csrfToken;
    var data    = {
        _token: token,
        keyword,
        id
    };
    $.ajax({
        url: urlUpdateItem,
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (data) {
        },
        complete: function () {
        }
    });
}
