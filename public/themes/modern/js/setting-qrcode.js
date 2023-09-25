jQuery(document).ready(function () 
{
	var $preview_container = $('#preview-container');
	var $parent = $preview_container.parent();
		
	$('#posisi-kartu').change(function(){
		posisi_kartu = $('#posisi-kartu').val();
		json = $.parseJSON($('#background-file').text());
		url_bg = base_url + 'public/images/kartu/' + json['background_' + posisi_kartu];
		
		if (url_bg) {
			$("<img/>")
				.on('load', function() 
				{
					$preview_container.find('img').remove();
					$(this).css('width', '100%').appendTo($preview_container);
				}).on('error', function(xhr) {
					alert('Error: lihat console');
					console.log(xhr);
				})
				.attr("src", url_bg);
		}
	});
	
	$('#content-field-database, #padding-qrcode, #size-pixel, #ecc-level, #version').change(function() {
		$('#btn-preview-qrcode').trigger('click');
	});
	
	$('#padding-qrcode').change(function() {
		$('.qrcode-container').css('padding', this.value);
	});
	
	$('#btn-preview-qrcode').click(function(e){
		// alert();
		e.preventDefault();
		// position = {};
		$form = $('#form-qrcode');
		data = $form.serialize();
		$.ajax({
			method : 'GET',
			data: data,
			url : module_url + '?action=preview-qrcode',
			success: function(data) {
				$('.qrcode-container').html(data);
			},
			error: function() {
				
			}
		});
	});
	
	$('#content-jenis').change(function() {
		if (this.value == 'field_database') {
			$('#content-global-text').hide();
			$('#content-field-database').show();
		} else {
			$('#content-global-text').show();
			$('#content-field-database').hide();
		}
		$('#btn-preview-qrcode').trigger('click');
	});
	
	let setting_printer = JSON.parse($('#setting-printer').text());
	
	$('input[type="range"]').on('input', function() {
		$(this).parent().find('input').val(this.value);
	})
	
	$('input[type="number"]').on('keyup change', function() {
		$(this).parent().prev().val(this.value).trigger('input');
	})
	
	$('#qrcode-margin-left-slider').on('input', function() {
		let new_margin_left = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		let kartu_container_width = $preview_container.width();
		let qrcode_container_width = $('.qrcode-container').outerWidth();
	
		if (new_margin_left + qrcode_container_width > kartu_container_width) {
			new_margin_left = kartu_container_width - qrcode_container_width;
		}
		
		$('.qrcode-container').css('left', new_margin_left);
	})
	
	$('#qrcode-margin-left-input').on('keyup change', function() {
		$('#qrcode-margin-left-slider').val(this.value).trigger('input');
	})
	
	$('#qrcode-margin-top-slider').on('input', function() {
		let new_margin_top = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		let kartu_container_height = $preview_container.height();
		let qrcode_container_height = $('.qrcode-container').outerHeight();
	
		if (new_margin_top + qrcode_container_height > kartu_container_height) {
			new_margin_top = kartu_container_height - qrcode_container_height;
		}
		
		$('.qrcode-container').css('top', new_margin_top);
	})
	
	$('#qrcode-margin-top-input').on('keyup change', function() {
		$('#qrcode-margin-top-slider').val(this.value).trigger('input');
	})
	
	/* if ($('#preview-container').is(':visible')) {
		// $preview_container = $('#preview-container');
		// var parent_padding_left = parseFloat($parent.css('padding-left'));
		// var parent_padding_top = parseFloat($parent.css('padding-top'));
		
		// $qrcode_container = $('.qrcode-container');
		
		setDisplace();
	}
	
	function setDisplace() 
	{		
		var parent_padding_left = parseFloat($parent.css('padding-left'));
		var parent_padding_top = parseFloat($parent.css('padding-top'));
		var posisi_top = parseFloat($('#posisi-top').val()) + parent_padding_top;
		var posisi_left = parseFloat($('#posisi-left').val()) + parent_padding_left;
		
		$qrcode_container = $('.qrcode-container');

		$qrcode_container.css('top', posisi_top);
		$qrcode_container.css('left', posisi_left);
		
		const options = {
			constrain: true,
			// relativeTo: document.body,
			onTouchStop: setLastPosition,
			onMouseUp: setLastPosition,
			onMouseMove: detectPosition,
			onTouchMove: detectPosition
		};

		function detectPosition(el){
			position = {top:el.offsetTop - parent_padding_top, left: el.offsetLeft - parent_padding_left}
		}
		
		function setLastPosition(el){
			$('#posisi-top').val(el.offsetTop - parent_padding_top);
			$('#posisi-left').val(el.offsetLeft - parent_padding_left);
		}
						
		displace = window.displacejs;
		el_parent = document.getElementById('preview-container');
		el = el_parent.querySelector('.qrcode-container');
		
		displace(el, options);
	} */
});
