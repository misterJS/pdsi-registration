/**
* App		: Aplikasi Kartu Pelajar
* Author	: Agus Prawoto Hadi
* Year		: 2022
* Website	: jagowebdev.com
*/

jQuery(document).ready(function () {
	
	$('input[type="range"]').on('input', function() {
		$(this).parent().find('input').val(this.value);
	})
	
	$('input[type="number"]').on('keyup change', function() {
		$(this).parent().prev().val(this.value).trigger('input');
	})
	
	$('.switch-gunakan').click(function()
	{
		let $this = $(this);
		if (!this.checked) {
			bootbox.alert('Error: Harus ada setting yang aktif');
			return false;
		} else {
			let id = $this.attr('data-id');
			let gunakan = this.checked ? 1 : 0;
			$('.switch-gunakan').prop('disabled', true);
			$.ajax({
				type: 'post',
				url: base_url + 'settingprinter/set-default',
				data: 'id=' + id + '&submit=submit&gunakan=' + gunakan,
				success: function(data) {
					data = JSON.parse(data);
					$('.switch-gunakan').prop('disabled', false);
					if (data.status == 'ok') {
						$('.switch-gunakan').prop('checked', false);
						$this.prop('checked', true);
					} else {
						bootbox.alert(data.message);
						return false;
					}
				}, error: function(xhr) {
					bootbox.alert('Ajax error check console browser');
					console.log(xhr);
					$('.switch-gunakan').prop('disabled', false);
				}
			})
		}
		
	})
});