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
                        swal("End product success!", "", "success");
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
});
