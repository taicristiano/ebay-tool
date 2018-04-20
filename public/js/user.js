(function() {
    $('#select-type').on('change', function() {
        if ($(this).val() == typeGuestAdmin) {
            $('#group-select-authorization').show().find('select').prop('disabled', false);
        } else {
            $('#group-select-authorization').hide().find('select').prop('disabled', true);
        }
    })
})()