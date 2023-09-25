$(document).ready(function() {
	
	if ($('#table-result').length) {
		column = $.parseJSON($('#dataTables-column').html());
		$setting =$('#dataTables-setting');
		var order = "";
		if ($setting.length > 0) {
			setting = $.parseJSON($('#dataTables-setting').html());
			order = setting.order;
		}
		url = $('#dataTables-url').html();
		
		table =  $('#table-result').DataTable( {
			"processing": true,
			"serverSide": true,
			"scrollX": true,
			"order" : order,
			"ajax": {
				"url": url,
				"type": "POST"
			},
			"columnDefs": [ {
				"targets": [0,1,2,8],
				"orderable": false
				} ],
			"columns": column,
			"initComplete": function( settings, json ) {
				table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
					$row = $(this.node());
					/* this
						.child(
							$(
								'<tr>'+
									'<td>'+rowIdx+'.1</td>'+
									'<td>'+rowIdx+'.2</td>'+
									'<td>'+rowIdx+'.3</td>'+
									'<td>'+rowIdx+'.4</td>'+
								'</tr>'
							)
						)
						.show(); */
				} );
			 }
		} );
	}
	
	function checkChecked() {
		$checked = $('#table-result').children('tbody').find('input[type="checkbox"]:checked');
		
		if ($checked.length > 0) {
			$('#form-cetak').find('button[type="submit"]').removeClass('disabled').removeAttr('disabled');
			// $('.check-all').prop('checked', true);
		} else if ($checked.length ==0) {
			
			$('#form-cetak').find('button[type="submit"]').addClass('disabled').attr('disabled','disabled');
		}
	}

	function showPrintAll(print = true) {
		if (print) {
			$('button.btn-print-all').removeClass('disabled').removeAttr('disabled');
			// $('.check-all').prop('checked', true);
		} else {
			
			$('button.btn-print-all').addClass('disabled').attr('disabled','disabled');
		}
	}

	$('table').delegate('input.checkall', 'click', function(e) 
	{
		if ($(this).is(':checked')) {
			showPrintAll();
			$('table').find('input.checkbox').prop('checked', 'checked');
		} else {
			$('table').find('input.checkbox').prop('checked', false);
			showPrintAll(false);
		}
	})

	$('table').delegate('input.checkbox', 'click', function(e) {
		// alert();
		if ($(this).is(':checked')) {
			showPrintAll();
			not_checked = $('table').find('input.checkbox:not(:checked)').length;
			if (not_checked == 0) {
				$('table').find('input.checkall').prop('checked', 'checked');
			}
		} else {
			$('table').find('input.checkall').prop('checked', false);
			not_checked = $('table').find('input.checkbox:checked').length;
			// console.log(not_checked);
			if (not_checked == 0) {
				showPrintAll(false);
			}
		}

	})

	$('body').delegate('.kirim-email', 'click', function(e){
		e.preventDefault();
		$this = $(this)
		email = $this.attr('data-email');
		id = $this.attr('data-id');

		$swal =  Swal.fire({
			title: 'Memproses kartu',
			text: 'Mohon sabar menunggu...',
			showConfirmButton: false,
			allowOutsideClick: false,
			didOpen: function () {
			  	Swal.showLoading();
			},
			didClose () {
				Swal.hideLoading()
			},
		});

		$.ajax({
			type: "POST",
			url: base_url + "cetakkartu/pdf?id=" + id,
			data: 'email=' + email,
			dataType: "JSON",
			success: function(data) {
				className = data.status == 'ok' ? 'success' : 'error';
				title = data.status == 'ok' ? 'Sukses !!!' : 'Error !!!';
				$swal.close();
				Swal.fire({
					html: data.message,
					title: title,
					icon: className,
					showCloseButton: true,
					confirmButtonText: 'OK'
				})
			}, error: function (xhr) {
				console.log(xhr);
			}
			
		});
	})
})