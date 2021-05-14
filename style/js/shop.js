const base_url = "http://localhost/";
$(function() {
    $('.list-group-item').on('click', function() {
        $('.fa', this)
            .toggleClass('fa-chevron-right')
            .toggleClass('fa-chevron-down');
    });
    $('#draff_product').on('click', function(e) {

        e.preventDefault();
        // var form_data = $("#add-product-form").serialize();
        var formData = new FormData($("#add-product-form")[0]);
        var url = base_url + "index.php/Product/draffProduct";
        var settings = {
            "url": url,
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": formData
        };
        $.ajax(settings).done((result, status, error) => {
            console.log(result);
        });
        console.log("oke");
    });
});

/*function showLogin() {
	if ($("#login-nav").css('display') == 'none') {
		$("#login-nav").show();
	} else {
		$("#login-nav").hide();
	}
}*/