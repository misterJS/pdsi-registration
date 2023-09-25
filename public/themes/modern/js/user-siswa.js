/**
* Written by: Agus Prawoto Hadi
* Year		: 2021-2022
* Website	: https://jagowebdev.com
*/

jQuery(document).ready(function () {
	
	$('#reset-password-options').change(function() {
		if (this.value == 'Y') {
			$('#reset-password-input').show();
		} else {
			$('#reset-password-input').hide();
		}
	})
});