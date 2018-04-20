(function() {
    $('.sidebar-menu li').each(function() {
        if ((new RegExp('\\' + $(this).attr('id') + '\\.*')).test(currentRoute)) {
            $(this).animate().addClass('active');
        }
    });

    $('input[type=checkbox]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		increaseArea: '20%'
    });

    $('#select-role').on('change', function() {

    })
})()