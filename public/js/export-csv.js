jQuery(document).ready(function() {
	$('#btn-csv-full').click(function(event) {
		event.preventDefault();
		exportCsvByType('full')
	});
	$('#btn-csv-simple').click(function(event) {
		event.preventDefault();
		exportCsvByType('simple')
	});
});

function exportCsvByType(typeExport)
{
	type = $('#select-type').val();
	username = $('#user-name').val();
	$('#type_csv').val(typeExport);
	$('#type_user').val(type);
	$('#user_name').val(username);
	url = urlDownloadCsv + '?type_csv=full&type_user=' +  type + '&user_name=' + username;
	$("#export-csv").attr('action', encodeURI(url));
	$('#export-csv').submit();
}