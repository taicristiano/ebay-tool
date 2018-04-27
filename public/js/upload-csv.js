jQuery(document).ready(function() {
	$("#file_csv").change(function (){     
        var file = this.files[0];
        $("#file_name_csv").val(file.name);            
    });  
});