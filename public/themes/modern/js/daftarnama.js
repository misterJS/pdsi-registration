/**
* Written by: Agus Prawoto Hadi
* Year		: 2020
* Website	: jagowebdev.com
*/

jQuery(document).ready(function () {
	
	if ($('#table-result').length) {
		column = $.parseJSON($('#dataTables-column').html());
		$setting =$('#dataTables-setting');
		var order = "";
		if ($setting.length > 0) {
			setting = $.parseJSON($('#dataTables-setting').html());
			order = setting.order;
		}
		url = $('#dataTables-url').html();
		
		// console.log(order);
		/* $.ajax({
			'method' : 'POST'
			, 'url' : url
			, data : 
		});
		 */
		dataTables =  $('#table-result').DataTable( {
			"processing": true,
			"serverSide": true,
			"scrollX": true,
			"order" : order,
			"ajax": {
				"url": url,
				"type": "POST"
			},
			"columnDefs": [ {
				"targets": [0,1,8],
				"orderable": false
				} ],
			"columns": column
		} );
	}
	
	$('body').delegate('.btn-delete', 'click', function(e) {
		e.preventDefault();
		id = $(this).attr('data-id');
		$bootbox = bootbox.confirm({
			message: $(this).attr('data-delete-title'),
			callback: function(confirmed) {
				if (confirmed) {
					$button = $bootbox.find('button');
					$button.attr('disabled', 'disabled');
					$spinner = $('<div class="spinner-border spinner-border-sm me-2"></div>');
					$spinner.prependTo($bootbox.find('.bootbox-accept'));
					$.ajax({
						type: 'POST',
						url: base_url + 'daftarnama/ajaxDeleteData',
						data: 'id=' + id,
						dataType: 'json',
						success: function (data) {
							$bootbox.modal('hide');
							$spinner.remove();
							$button.removeAttr('disabled');

							if (data.status == 'ok') {
								const Toast = Swal.mixin({
									toast: true,
									position: 'top-end',
									showConfirmButton: false,
									timer: 2500,
									timerProgressBar: true,
									iconColor: 'white',
									customClass: {
										popup: 'bg-success text-light toast p-2'
									},
									didOpen: (toast) => {
										toast.addEventListener('mouseenter', Swal.stopTimer)
										toast.addEventListener('mouseleave', Swal.resumeTimer)
									}
								})
								Toast.fire({
									html: '<div class="toast-content"><i class="far fa-check-circle me-2"></i> Data berhasil dihapus</div>'
								})
								dataTables.draw();
							} else {
								show_alert('Error !!!', data.message, 'error');
							}
						},
						error: function (xhr) {
							$spinner.remove();
							$button.removeAttr('disabled');
							show_alert('Error !!!', xhr.responseText, 'error');
							console.log(xhr.responseText);
						}
					})
					return false;
				}
			},
			centerVertical: true
		});
	})

});