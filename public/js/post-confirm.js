jQuery(document).ready(function() {
    $(document).on("click", "#save", function() {
        swal("この商品を投稿しますでしょうか？", {
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
                    postProduct();
                    break;
             }
        });
    })

    $(document).on("click", "#back", function() {
        window.location.href = urlBack;
    })
    $(document).on("click", ".swal-button--confirm", function() {
        // window.location.href = urlBack;
    })
});

function postProduct()
{
    $('body').addClass('loading-ajax');
    var token = window.Laravel.csrfToken;
    var data = {
        _token: token,
    };
    $.ajax({
        url: urlPublishProduct,
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (data) {
            if (data.status) {
                swal("Success!", "Post product success!", "success");
            } else {
                swal("Error!", "Post product error!", "error");
            }
        },
        complete: function () {
            $('body').removeClass('loading-ajax');
        }
    });
}