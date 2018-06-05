(function() {
    $('#select-type').on('change', function() {
        if ($(this).val() == typeGuestAdmin) {
            $('#group-select-authorization').show().find('select').prop('disabled', false);
            $('#group-regist-limit').show().find('input').prop('disabled', false);
            $('#group-post-limit').show().find('input').prop('disabled', false);
        } else {
            $('#group-select-authorization').hide().find('select').prop('disabled', true);
            $('#group-regist-limit').hide().find('input').prop('disabled', true);
            $('#group-post-limit').hide().find('input').prop('disabled', true);
        }
    })
    $('#select-introducer').select2({
        ajax: {
            url: fetchUserUrl,
            dataType: 'json',
            data: function(params) {
                var query = {
                    search: params.term,
                    limit: 10,
                    page: params.page || 1
                }
                // Query parameters will be ?search=[term]&page=[page]
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
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });
    $('#input-start-date').datepicker({
        format: 'yyyy/mm/dd',
    });
})()