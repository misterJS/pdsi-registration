/**
* App		: PHP Admin Template
* Author	: Agus Prawoto Hadi
* Year		: 2021
* Website	: jagowebdev.com
*/
let px = 0.393700787;


jQuery(document).ready(function () {
	
	$('.flatpickr').flatpickr({
		dateFormat: "d-m-Y"
	});
	
	$('body').delegate('.btn-delete-setting', 'click', function() {
		
		$this = $(this);
				
		$bootbox =  bootbox.dialog({
			title: 'Delete Data',
			message: 'Hapus data setting?',
			buttons: {
				cancel: {
					label: 'Cancel'
				},
				success: {
					label: 'Submit',
					className: 'btn-success submit',
					callback: function() 
					{
						$spinner = $('<span class="spinner-border spinner-border-sm me-2"></span>').prependTo($this);
						$this.attr('disabled', 'disabled');
						
						$bootbox_button = $bootbox.find('button').attr('disabled', 'disabled');
						$bootbox_submit = $bootbox.find('.submit');
						$bootbox_spinner = $('<span class="spinner-border spinner-border-sm me-2"></span>').prependTo($bootbox_submit);
						
		
						$.ajax({
							url: base_url + 'desainkartu/delete',
							data: 'id=' + $this.attr('data-id') + '&delete=delete',
							type: 'POST',
							dataType: 'json',
							success: function(data) {
								$spinner.remove();
								$this.removeAttr('disabled');
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
									
								} else {
									show_alert('Error !!!', data.message, 'error');
								}
								
								$bootbox.modal('hide');
								dataTables.draw();
							}, error: function(xhr) {
								$spinner.remove();
								$this.removeAttr('disabled');
								
								$bootbox_button.removeAttr('disabled');
								$bootbox_spinner.remove();
						
								console.log(xhr);
								show_alert('Error !!!', xhr.responseText, 'error');
							}
						})
						return false;
					}
				}
			}
		});
	});
	
	if ($('#table-result').length) {
		column = $.parseJSON($('#dataTables-column').html());
		$setting =$('#dataTables-setting');
		var order = "";
		if ($setting.length > 0) {
			setting = $.parseJSON($('#dataTables-setting').html());
			order = setting.order;
		}
		url = $('#dataTables-url').html();
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
				"targets": [5],
				"orderable": false
				} ],
			"columns": column,
		} );
	}
	
	if ($('#printer').length > 0) {
		setting_printer = JSON.parse($('#printer').val());
		layout_kartu = JSON.parse($('#setting-kartu').val());
	}
	
	$('form').submit(function(){
		return false;
	})
	
	$('#submit').click(function(e) {
		e.preventDefault();
		$this = $(this);
		$spinner = $('<span class="spinner-border spinner-border-sm me-2"></span>').prependTo($this);
		$this.attr('disabled', 'disabled');
	
		let form = $('form')[0];
		let form_data = new FormData(form);
		form_data.append('submit', 'submit');
		
		if ($('#id').val()) {
			url = base_url + 'desainkartu/edit';
		} else {
			url = base_url + 'desainkartu/add';
		}
		
		$.ajax({
			url: url,
			data: form_data,
			processData: false,
			contentType: false,
			type: 'POST',
			dataType: 'json',
			success: function(data) {
				console.log(data);
				$spinner.remove();
				$this.removeAttr('disabled');
				if (data.status == 'ok') {
					$('form').find('.file').val('');
					$('form').find('.btn-reset').attr('disabled', 'disabled');
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
						html: '<div class="toast-content"><i class="far fa-check-circle me-2"></i> Data berhasil disimpan</div>'
					})
					$('#id').val(data.id);
				} else {
					show_alert('Error !!!', data.message, 'error');
				}
			}, error: function(xhr) {
				$spinner.remove();
				$this.removeAttr('disabled');
				console.log(xhr);
				show_alert('Error !!!', xhr.responseText, 'error');
			}
		})
	})
		
	$('input[type="range"]').on('input', function() {
		$(this).parent().find('input').val(this.value);
	})
	
	// ########### Kartu
	
	$('#kartu-reset-lanscape').on('click', function() {
		$('#kartu-width-slider').val('85.6').trigger('input');
		$('#kartu-height-slider').val('54').trigger('input');
	})
	
	$('#kartu-reset-portrait').on('click', function() {
		$('#kartu-width-slider').val('54').trigger('input');
		$('#kartu-height-slider').val('85.6').trigger('input');
	})
	
	$('#kartu-width-slider').on('input', function() {
		let curr_width = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-content-container').css('width', curr_width + 'px');
	})
	
	$('#kartu-width-input').on('keyup change', function() {
		$('#kartu-width-slider').val(this.value).trigger('input');
	})
	
	$('#kartu-height-slider').on('input', function() {
		let curr_height = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-content-container').css('height', curr_height + 'px');
	})
	
	$('#kartu-height-input').on('keyup change', function() {
		$('#kartu-height-slider').val(this.value).trigger('input');
	})
	
	//-- Kartu
	
	// ########### DATA SISWA
	
	$('#data-margin-left').on('input', function() {
		let margin_left = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-detail').css('left', margin_left);
	})
	
	$('#data-margin-left-input').on('keyup change', function() {
		$('#data-margin-left').val(this.value).trigger('input');
	})
	
	$('#data-margin-left-center').click(function() {
		kartu_container_width = $('.kartu-content-container').width();
		data_container_width = $('.kartu-detail').width();
		let margin_left = kartu_container_width / 2 - (data_container_width / 2);
		let value = 25.4 * margin_left / parseInt(setting_printer.dpi);
		$('#data-margin-left').val(value).trigger('input');
		
	})
		
	$('#data-margin-top').on('input', function() {
		let margin_top = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-detail').css('top', margin_top);
	})
	
	$('#data-margin-top-input').on('keyup change', function() {
		$('#data-margin-top').val(this.value).trigger('input');
	})
	
	$('#data-depan-label-width-slider').on('input', function() {
		$('#style-data-label-width').remove();
		let new_width = parseInt(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('head').append('<style id="style-data-label-width">.kartu-detail .label { width: ' + new_width + 'px !important}</style>');
	})
	
	$('#data-depan-label-width-input').on('keyup change', function() {
		$('#data-depan-label-width-slider').val(this.value).trigger('input');
	})
	
	$('#data-depan-line-height-slider').on('input', function() {
		$('#style-data-depan-line-height').remove();
		$('head').append('<style id="style-data-depan-line-height">.kartu-detail td { line-height: ' + this.value + 'px !important}</style>');
	})
	
	$('#data-depan-line-height-input').on('keyup change', function() {
		$('#data-depan-line-height-slider').val(this.value).trigger('input');
	})
	
	$('#data-depan-font-size-slider').on('input', function() {
		$(this).parent().find('output').val(this.value);
		$('#style-data-font-size').remove();
		$('head').append('<style id="style-data-font-size">.kartu-detail td { font-size: ' + this.value + 'px !important}</style>');
	})
	
	$('#data-depan-font-size-input').on('keyup change', function() {
		$('#data-depan-font-size-slider').val(this.value).trigger('input');
	})
	
	$('#data-font-family').change(function() {
		$('#style-data-font-family').remove();
		$('head').append('<style id="style-data-font-family">.kartu-detail td { font-family: ' + this.value + ' !important}</style>');
	})
	
	let $bootbox = '';
	$(document).delegate('.btn-edit', 'click', function() {
		let $this = $(this);
		let $container = $this.parents('.data-item-container').eq(0);
		
		$bootbox =  bootbox.dialog({
			title: 'Edit Data',
			message: $('#form-data-item').html(),
			buttons: {
				cancel: {
					label: 'Cancel'
				},
				success: {
					label: 'Submit',
					className: 'btn-success submit',
					callback: function() 
					{
						let new_label = $bootbox.find('.form-data-label').val();
						let new_content = $bootbox.find('.form-data-content').val();
						$container.find('.judul-data').val(new_label);
						$container.find('.item-label').html(new_label);
						$container.find('.pattern').val(new_content);
						generateTableData();
					}
				}
			}
		});
		
		$bootbox.find('.form-data-label').val($container.find('.judul-data').val());
		$bootbox.find('.form-data-content').val($container.find('.pattern').val());
	});
	
	$(document).delegate('.btn-add-data-item-column', 'click', function() {
		let current_value = $bootbox.find('.form-data-content').val();
		$bootbox.find('.form-data-content').val( current_value + this.value )
	})
	
	$(document).delegate('.btn-delete', 'click', function() {
		let $container = $(this).parents('.data-item-container');
		if ($('#list-data-item-container').find('.data-item-container').length == 1) {
			$container.find('input').val('');
			$container.hide();
		} else {
			$container.remove();
		}
		generateTableData();
	})
	
	$('#add-data-depan').click(function(){
		let $this = $(this);
		
		$bootbox =  bootbox.dialog({
			title: 'Add Data',
			message: $('#form-data-item').html(),
			buttons: {
				cancel: {
					label: 'Cancel'
				},
				success: {
					label: 'Submit',
					className: 'btn-success submit',
					callback: function() 
					{
						let new_label = $bootbox.find('.form-data-label').val();
						let new_content = $bootbox.find('.form-data-content').val();
						$new_item = $('.data-item-container').eq(0).clone();
						$new_item.find('.judul-data').val(new_label);
						$new_item.find('.item-label').html(new_label);
						$new_item.find('.pattern').val(new_content);
						$new_item.appendTo($('#list-data-item-container'));
						$new_item.show();
						generateTableData();
					}
				}
			}
		});
	});
	
	$('.btn-data-depan-text-align').click(function(){
		$('#style-data-depan-text-align').remove();
		$('.btn-data-depan-text-align').removeClass('btn-secondary');
		$('.btn-data-depan-text-align').addClass('btn-outline-secondary');
		$(this).removeClass('btn-outline-secondary');
		$(this).addClass('btn-secondary');
		$(this).parent().parent().find('input').val(this.value);
		$('head').append('<style id="style-data-depan-text-align">.kartu-detail td { text-align: ' + this.value + ' !important}</style>');
	})
	
	$('.btn-data-depan-fw').click(function(){
		if (this.value == 'normal') {
			$('.kartu-detail').css('font-weight', 'normal');
		} else {
			$('.kartu-detail').css('font-weight', 'bold');
		}
		
		$('.btn-data-depan-fw').removeClass('btn-secondary');
		$('.btn-data-depan-fw').addClass('btn-outline-secondary');
		$(this).removeClass('btn-outline-secondary');
		$(this).addClass('btn-secondary');
		$(this).parent().parent().find('input').val(this.value);
		$('#style-data-depan-fw').remove();
		$('head').append('<style id="style-data-depan-fw">.kartu-detail td { font-weight: ' + this.value + ' !important}</style>');
	})
	 
	 dragKategori = null;
	 
	 function generateTableData() 
	 {
		$table = $('.kartu-detail').children('table');
		let siswa = JSON.parse($('#siswa').val());
		let data_item = Object.keys(siswa);
		data_item.sort();
		data_item.reverse();
		
		let table = '<table>';
		let tampilkan_label =  $('#tampilkan-label').val();
		$('.pattern').each(function(i, elm) {
			let $elm = $(elm);
			let value = $elm.val();
			data_item.map( function(val_item) {
				
				content = siswa[val_item];
				if (value.indexOf(val_item) != -1) {
					if (val_item.indexOf('tgl') != -1) {
						split = content.split('-');
						content = split[2] + ' ' + namaBulan( split[1] * 1 ) + ' ' + split[0];
					}
					value = value.replaceAll(val_item, content);
				}
			})
			
			if (tampilkan_label == 'Y') {
				table += '<tr><td class="label">' + $('.judul-data').eq(i).val() + '</td><td style="padding:0 2px">:</td><td>' + value + '</td></tr>';
			} else {
				table += '<tr><td>' + value + '</td></tr>';
			}
		})
		
		table += '</table>';
		$table.remove();
		$('.kartu-detail').append(table);
	 }
	 
	 $('#tampilkan-label').change(function() {
		 generateTableData();
	 })
	 
	 function initDragKategori() {
		dragKategori = dragula([document.getElementById('selected-data-item-container'), document.getElementById('list-data-item-container')], {
			moves: function (el, container, handle) {
				return handle.classList.contains('grip-handler') || handle.parentNode.classList.contains('grip-handler');
			}
		});
		
		dragKategori.on('dragend', function(el)
		{
			generateTableData();
			
			/* id = $('#id-setting-layar').val();
			$input_urut = $('.selected-kategori-panel').find('input[name="urut[]"]');
			
			list_id = [];
			$input_urut.each(function(i, elm){
				list_id.push( $(elm).val() );
			});
			
			list_id_kategori = JSON.stringify(list_id);
			$.ajax({
				type : 'post',
				url : base_url + '/layar-monitor-setting/ajaxUpdateKategori',
				data : 'submit=submit&id_setting_layar=' + id + '&list_id_kategori=' + list_id_kategori,
				dataType : 'JSON',
				success : function(data) {
					if (data.status == 'error') {
						show_alert('Error !!!', data.message, 'error');
					}
				}, error : function (xhr) {
					show_alert('Ajax Error !!!', xhr.responseJSON.message + '<br/><strong>Note</strong>: Detail error ada di console browser', 'error');
					console.log(xhr);
				}
				
			}) */
		});
	 }
	
	initDragKategori();
	
	//-- DATA SISWA
	
	// ########### Foto
	
	function setFotoStyle() {
		$img_container = $('.kartu-foto');
		$img = $('.kartu-foto').find('img');
		
		let img_container_width = $img_container.width();
		let img_container_height = $img_container.height();
		let img_width = $img.width();
		let img_height = $img.height();
		
		if (img_container_width < img_width) {
			$img.css({width:'100%', height : 'auto'});
		}
		
		if (img_container_height < img_height) {
			$img.css({width:'auto', height : '100%'});
		}
	}
	
	$('#foto-width-slider').on('input', function() {
		let new_width = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-foto').width(new_width);
		setFotoStyle();
	})
	
	$('#foto-width-input').on('keyup change', function() {
		$('#foto-width-slider').val(this.value).trigger('input');
	})
	
	$('#foto-height-slider').on('input', function() {
		let new_height = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-foto').css('height', new_height);
		setFotoStyle();
	})
	
	$('#foto-height-input').on('keyup change', function() {
		$('#foto-height-slider').val(this.value).trigger('input');
	})
	
	$('#foto-margin-left-slider').on('input', function() {
		let new_left = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-foto').css('left', new_left);
	})
	
	$('#foto-margin-left-input').on('keyup change', function() {
		$('#foto-margin-left-slider').val(this.value).trigger('input');
	})
	
	$('#foto-margin-top-slider').on('input', function() {
		let new_top = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-foto').css('top', new_top);
	})
	
	$('#foto-margin-top-input').on('keyup change', function() {
		$('#foto-margin-top-slider').val(this.value).trigger('input');
	})
	
	//Center
	$('#foto-margin-left-center').click(function() {
		kartu_container_width = $('.kartu-content-container').width();
		foto_container_width = $('.kartu-foto').width();
		let margin_left = kartu_container_width / 2 - (foto_container_width / 2);
		let value = 25.4 * margin_left / parseInt(setting_printer.dpi);
		$('#foto-margin-left-slider').val(value).trigger('input');
	})
	
	$('#foto-margin-top-center').click(function() {
		kartu_container_height = $('.kartu-content-container').height();
		foto_container_height = $('.kartu-foto').height();
		let margin_top = kartu_container_height / 2 - (foto_container_height / 2);
		let value = 25.4 * margin_top / parseInt(setting_printer.dpi);
		$('#foto-margin-top-slider').val(value).trigger('input');
	})
	//-- Foto
	
	// ##### TANDA TANGAN
	$('#ttd-gunakan').change(function() {
		if (this.value == 'Y') {
			$('#tanda-tangan-setting-container, #ttd-container').show();
		} else {
			$('#tanda-tangan-setting-container, #ttd-container').hide();
		}
	});
	$('#ttd-posisi').change(function() {
		
		if (this.value == 'depan') {
			$('#ttd-container').detach().appendTo('.kartu-depan');
		} else {
			$('#ttd-container').detach().appendTo('.kartu-belakang');
		}
	})
	
	// ##### TANDA TANGAN TEXT
	
	function mmToPx(value) {
		return parseFloat(value) * parseInt(setting_printer.dpi) / 25.4;
	}
	function setSpaceSignHeight(value) {
		let new_height = mmToPx(value);
		$('#ttd-text-space-sign').css('height', new_height);
	}
	$('#ttd-text-space-sign-slider').on('input', function() {
		setSpaceSignHeight(this.value);
	})
	$('#ttd-text-space-sign-input').on('keyup change', function() {
		$('#ttd-text-space-sign-slider').val(this.value).trigger('input');
	})
	
	function setSpaceSignMarginLeft(value) {
		let new_margin_left = mmToPx(value);
		$('.kartu-tandatangan').css('left', new_margin_left);
	}
	$('#ttd-text-margin-left-slider').on('input', function() {
		setSpaceSignMarginLeft(this.value);
	})
	$('#ttd-text-margin-left-input').on('keyup change', function() {
		$('#ttd-text-margin-left-slider').val(this.value).trigger('input');
	})
	$('#ttd-text-margin-left-center').on('click', function() {
		let kartu_container_width = $('.kartu-content-container').width();
		let data_container_width = $('.kartu-tandatangan').width();
		
		text_align = $('.ttd-text-align-selected').attr('data-align');
		if (text_align == 'center') {
			margin_left = kartu_container_width / 2 - (data_container_width / 2);
		
		} else {
			margin_left = kartu_container_width / 2;
		}
		let value = 25.4 * margin_left / parseInt(setting_printer.dpi);
		$('#ttd-text-margin-left-slider').val(value).trigger('input');
	})
	
	function setSpaceSignMarginTop(value) {
		let new_margin_left = mmToPx(value);
		$('.kartu-tandatangan').css('top', new_margin_left);
	}
	$('#ttd-text-margin-top-slider').on('input', function() {
		setSpaceSignMarginTop(this.value);
	})
	$('#ttd-text-margin-top-input').on('keyup change', function() {
		$('#ttd-text-margin-top-slider').val(this.value).trigger('input');
	})
	
	$('.btn-ttd-text-align').click(function() {
		
		$('.btn-ttd-text-align').removeClass('ttd-text-align-selected');
		$('.btn-ttd-text-align').removeClass('btn-secondary');
		$('.btn-ttd-text-align').addClass('btn-outline-secondary');
		$(this).removeClass('btn-outline-secondary');
		$(this).addClass('btn-secondary');
		$(this).addClass('ttd-text-align-selected');
		$(this).parent().parent().find('input').val(this.value);
		$('.kartu-tandatangan').css('text-align', this.value);
	});
	
	$('.btn-edit-ttd-text').click(function() {
		let form = '<div class="row">' +
						'<label class="col-sm-3">Konten</label>' +
						'<div class="col-sm-8">' +
							'<textarea style="height:160px" class="form-control">' + $('#ttd-text').val() + '</textarea>' +
						'</div>' +
					'</div>';
					
		$bootbox =  bootbox.dialog({
			title: 'Edit Data',
			message: form,
			buttons: {
				cancel: {
					label: 'Cancel'
				},
				success: {
					label: 'OK',
					className: 'btn-success submit',
					callback: function() 
					{
						let curr_date = new Date();
						let input_content = $bootbox.find('textarea').val().replaceAll('{{YEAR}}', curr_date.getFullYear());
						let split = input_content.split("\n");
						let new_content = '';
						let use_space = false;
						split.map(function(v) {
							if (v) {
								new_content += '<p>' + v + '</p>';
							} else {
								if (!use_space) {
									new_content += '<div id="ttd-text-space-sign" style="height:11.811023622047px;"></div>';
									use_space = true;
								}
							}
						})
						$('.kartu-tandatangan').empty().html(new_content);
						$('#ttd-text').val(input_content);
						// console.log(split);
					}
				}
			}
		});
	});
	
	$('#ttd-text-font-family').on('change', function() {
		$('.kartu-tandatangan').css('font-family', this.value);
	})
	
	$('#ttd-text-font-size-slider').on('input', function() {
		$('.kartu-tandatangan').css('font-size', this.value + 'px');
	})
	
	$('#ttd-text-font-size-input').on('keyup change', function() {
		$('#ttd-text-font-size-slider').val(this.value).trigger('input');
	})
	
	$('.btn-ttd-text-fw').on('click', function() {
		$('.btn-ttd-text-fw').removeClass('btn-secondary');
		$('.btn-ttd-text-fw').addClass('btn-outline-secondary');
		$(this).removeClass('btn-outline-secondary');
		$(this).addClass('btn-secondary');
		$(this).parent().parent().find('input').val(this.value);
		$('.kartu-tandatangan').css('font-weight', this.value);
	})
	
	// ##### TANDA TANGAN SIGN
	
	function setSignStyle() {
		let $img_container = $('.kartu-tandatangan-sign');
		let $img = $('.kartu-tandatangan-sign').find('img');
		
		let img_container_width = $img_container.width();
		let img_container_height = $img_container.height();
		let img_width = $img.width();
		let img_height = $img.height();
		
		if (img_container_width < img_width) {
			$img.css({width:'100%', height : 'auto'});
		}
		
		if (img_container_height < img_height) {
			$img.css({width:'auto', height : '100%'});
		}
	}

	$('#ttd-sign-width-slider').on('input', function() {
		let new_width = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-tandatangan-sign').width(new_width);
		setSignStyle();
	})
	
	$('#ttd-sign-width-input').on('keyup change', function() {
		$('#ttd-sign-width-slider').val(this.value).trigger('input');
	})
	
	$('#ttd-sign-height-slider').on('input', function() {
		let new_height = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-tandatangan-sign').css('height', new_height);
		setSignStyle();	
	})
	
	$('#ttd-sign-height-input').on('keyup change', function() {
		$('#ttd-sign-height-slider').val(this.value).trigger('input');
	})
	
	$('#ttd-sign-margin-left-slider').on('input', function() {
		let new_left = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-tandatangan-sign').css('left', new_left);
	})
	
	$('#ttd-sign-margin-left-input').on('keyup change', function() {
		$('#ttd-sign-margin-left-slider').val(this.value).trigger('input');
	})
	
	$('#ttd-sign-margin-top-slider').on('input', function() {
		let new_top = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-tandatangan-sign').css('top', new_top);
	})
	
	$('#ttd-sign-margin-top-input').on('keyup change', function() {
		$('#ttd-sign-margin-top-slider').val(this.value).trigger('input');
	})
	
	// ##### TANDA TANGAN CAP
	
	function setCapStyle() {
		let $img_container = $('.kartu-tandatangan-cap');
		let $img = $('.kartu-tandatangan-cap').find('img');
		
		let img_container_width = $img_container.width();
		let img_container_height = $img_container.height();
		let img_width = $img.width();
		let img_height = $img.height();
		
		if (img_container_width < img_width) {
			$img.css({width:'100%', height : 'auto'});
		}
		
		if (img_container_height < img_height) {
			$img.css({width:'auto', height : '100%'});
		}
	}

	$('#ttd-cap-width-slider').on('input', function() {
		let new_width = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-tandatangan-cap').width(new_width);
		setCapStyle();
	})
	
	$('#ttd-cap-width-input').on('keyup change', function() {
		$('#ttd-cap-width-slider').val(this.value).trigger('input');
	})
	
	$('#ttd-cap-height-slider').on('input', function() {
		let new_height = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-tandatangan-cap').css('height', new_height);
		setCapStyle();	
	})
	
	$('#ttd-cap-height-input').on('keyup change', function() {
		$('#ttd-cap-height-slider').val(this.value).trigger('input');
	})
	
	$('#ttd-cap-margin-left-slider').on('input', function() {
		let new_left = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-tandatangan-cap').css('left', new_left);
	})
	
	$('#ttd-cap-margin-left-input').on('keyup change', function() {
		$('#ttd-cap-margin-left-slider').val(this.value).trigger('input');
	})
	
	$('#ttd-cap-margin-top-slider').on('input', function() {
		let new_top = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.kartu-tandatangan-cap').css('top', new_top);
	})
	
	$('#ttd-cap-margin-top-input').on('keyup change', function() {
		$('#ttd-cap-margin-top-slider').val(this.value).trigger('input');
	})
	
	// ##### QRCODE
	$('#qrcode-gunakan').change(function() {
		if (this.value == 'Y') {
			$('#qrcode-container-setting, .qrcode-container').show();
		} else {
			$('#qrcode-container-setting, .qrcode-container').hide();
		}
	})
	
	$('#qrcode-posisi').change( function() {
		if (this.value == 'depan') {
			$('.qrcode-container').detach().appendTo('.kartu-depan');
		} else {
			$('.qrcode-container').detach().appendTo('.kartu-belakang');
		}
	})
	
	$('#qrcode-margin-left-slider').on('input', function() {
		let new_margin_left = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.qrcode-container').css('left', new_margin_left);
	})
	
	$('#qrcode-margin-left-input').on('keyup change', function() {
		$('#qrcode-margin-left-slider').val(this.value).trigger('input');
	})
	
	$('#qrcode-margin-top-slider').on('input', function() {
		let new_margin_top = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.qrcode-container').css('top', new_margin_top);
	})
	
	$('#qrcode-margin-top-input').on('keyup change', function() {
		$('#qrcode-margin-top-slider').val(this.value).trigger('input');
	})
	
	function previewQrcode() {
		$form = $('form');
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
	}
	
	$('#qrcode-content-jenis').change(function() {
		if (this.value == 'field_database') {
			$('#qrcode-content-global-text').hide();
			$('#qrcode-content-field-database').show();
		} else {
			$('#qrcode-content-global-text').show();
			$('#qrcode-content-field-database').hide();
		}
		previewQrcode();
	});
	
	$('#qrcode-content-field-database, #qrcode-size-pixel, #qrcode-ecc-level, #qrcode-version').change(function() {
		previewQrcode();
	});
	
	$('#qrcode-padding').change(function() {
		$('.qrcode-container').css('padding', this.value);
	});
	
	$('#btn-preview-qrcode').click(function(e){
		previewQrcode();
	});
	
	// ##### BERLAKU
	$('#berlaku-gunakan').change(function() {
		if (this.value == 'Y') {
			$('#berlaku-container-setting, .berlaku-container').show();
		} else {
			$('#berlaku-container-setting, .berlaku-container').hide();
		}
	})
	
	$('#berlaku-posisi').change( function() {
		if (this.value == 'depan') {
			$('.berlaku-container').detach().appendTo('.kartu-depan');
		} else {
			$('.berlaku-container').detach().appendTo('.kartu-belakang');
		}
	})
	
	$('#berlaku-margin-left-slider').on('input', function() {
		let new_margin_left = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.berlaku-container').css('left', new_margin_left);
	})
	
	$('#berlaku-margin-left-input').on('keyup change', function() {
		$('#berlaku-margin-left-slider').val(this.value).trigger('input');
	})
	
	$('#berlaku-margin-top-slider').on('input', function() {
		let new_margin_top = parseFloat(this.value) * parseInt(setting_printer.dpi) / 25.4;
		$('.berlaku-container').css('top', new_margin_top);
	})
	
	$('#berlaku-margin-top-input').on('keyup change', function() {
		$('#berlaku-margin-top-slider').val(this.value).trigger('input');
	})
	
	$('#berlaku-font-size-slider').on('input', function() {
		$('.berlaku-container').css('font-size', this.value + 'px');
	})
	
	$('#berlaku-font-size-input').on('keyup change', function() {
		$('#berlaku-font-size-slider').val(this.value).trigger('input');
	})
	
	$('#berlaku-font-family').change(function() {
		$('.berlaku-container').css('font-family', this.value);
	})
	
	$('.btn-berlaku-fw').click(function(){
		if (this.value == 'normal') {
			$('.berlaku-container').css('font-weight', 'normal');
		} else {
			$('.berlaku-container').css('font-weight', 'bold');
		}
		
		$('.btn-berlaku-fw').removeClass('btn-secondary');
		$('.btn-berlaku-fw').addClass('btn-outline-secondary');
		$(this).removeClass('btn-outline-secondary');
		$(this).addClass('btn-secondary');
		$(this).parent().parent().find('input').val(this.value);
	})
	
	function generateBerlakuPeriode() {
		let jenis = $('#berlaku-jenis').val();
		if (jenis == 'periode') {
			let split = $('#berlaku-tanggal').val().split('-');
			let tanggal = split[0] + ' ' + namaBulan(split[1]) + ' ' + split[2];
			$('.berlaku-container').html( $('#berlaku-periode-prefix').val().trim() + ' ' + tanggal );
		} else {
			$('.berlaku-container').html( $('#custom-text').val().trim() );
		}
	}
	
	$('#berlaku-periode-prefix, #custom-text').keyup(function() {
		generateBerlakuPeriode();
	});
	
	$('#berlaku-tanggal').change(function() {
		generateBerlakuPeriode();
	})
	
	$('#berlaku-jenis').change(function() {
		if (this.value == 'periode') {
			$('#periode').show();
			$('#custom-text').hide();
		} else {
			$('#periode').hide();
			$('#custom-text').show();
		}
		
		generateBerlakuPeriode();
	})
	
	// LAIN LAIN
	$('table').delegate('.switch-gunakan', 'click', function()
	{
		let $this = $(this);
		if (!this.checked) {
			bootbox.alert('Error: Harus ada setting yang aktif');
			return false;
		} else {
			let id = $this.attr('data-id');
			let gunakan = this.checked ? 'Y' : 'N';
			$('.switch-gunakan').prop('disabled', true);
			$.ajax({
				type: 'post',
				url: base_url + 'desainkartu/set-default',
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
	
	$('.show-info').click(function(e) {
		e.preventDefault();
		bootbox.alert($(this).attr('data-info'));
	})
});