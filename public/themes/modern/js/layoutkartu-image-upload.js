/**
* App		: PHP Admin Template
* Author	: Agus Prawoto Hadi
* Year		: 2021
* Website	: jagowebdev.com
*/

jQuery(document).ready(function () {
	function bytesToSize(bytes) {
		var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		if (bytes == 0) return 'n/a';
		var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
	};
	
	$('.btn-reset-background').click(function(){
		let posisi = $(this).attr('data-posisi');
		let $this = $(this);
		$('#style-background-' + posisi).remove();
		$this.parent().find('input').val('');
		$this.attr('disabled', 'disabled');
	});
	
	$('.file-background-depan').change(function(e) 
	{
		loadImage(this, 'depan');
	});
	
	$('.file-background-belakang').change(function(e) 
	{
		loadImage(this, 'belakang');
	});
	
	function loadImage(obj, posisi) {
		
		file = obj.files[0];
		$this = $(obj);

		if ($this.val() == '') {
			$this.parent().find('.btn-reset-background').trigger('click');
			return false;
		}
		
		var reader = new FileReader();
		reader.onload = (function(e) {
			
			var img = new Image;
			img.src = reader.result;
            img.onload = function() {
				$('#style-background-' + posisi).remove();
				$('head').append('<style id="style-background-' + posisi + '">.kartu-' + posisi + '{ background-image: url("' + e.target.result + '") !important }</style>');
			
            };
			$this.parent().find('.btn-reset-background').removeAttr('disabled');
		});
		
		reader.readAsDataURL(file); 
	}
	
	// ##### SIGN Image
	$('.btn-reset-ttd-sign-image').click(function(){
		let $image = $('#ttd-sign-image');
		let image_file = $image.parent().find('input').val();
		let $this = $(this);
		
		$image.attr('src', base_url + kartu_path + image_file);
		$this.attr('disabled', 'disabled');
		$this.parent().find('input').val('');
	});
	
	$('.file-ttd-sign-image').change(function(e) 
	{
		file = this.files[0];
		$this = $(this);

		if ($this.val() == '') {
			$this.parent().find('.btn-reset-ttd-sign-image').trigger('click');
			return false;
		}
		
		var reader = new FileReader();
		reader.onload = (function(e) {
			
			var img = new Image;
			img.src = reader.result;
            img.onload = function() {
				$('#ttd-sign-image').attr('src', e.target.result);
            };
			$this.parent().find('.btn-reset-ttd-sign-image').removeAttr('disabled');
		});
		
		reader.readAsDataURL(file); 
	});
	
	// ##### CAP Image
	$('.btn-reset-ttd-cap-image').click(function(){
		let $image = $('#ttd-cap-image');
		let image_file = $image.parent().find('input').val();
		let $this = $(this);
		
		$image.attr('src', base_url + kartu_path + image_file);
		$this.attr('disabled', 'disabled');
		$this.parent().find('input').val('');
	});
	
	$('.file-ttd-cap-image').change(function(e) 
	{
		file = this.files[0];
		$this = $(this);

		if ($this.val() == '') {
			$this.parent().find('.btn-reset-ttd-cap-image').trigger('click');
			return false;
		}
		
		var reader = new FileReader();
		reader.onload = (function(e) {
			
			var img = new Image;
			img.src = reader.result;
            img.onload = function() {
				$('#ttd-cap-image').attr('src', e.target.result);
            };
			$this.parent().find('.btn-reset-ttd-cap-image').removeAttr('disabled');
		});
		
		reader.readAsDataURL(file); 
	});
});