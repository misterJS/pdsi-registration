$(document).ready(function() {
	
	function dynamicColors() {
		var r = Math.floor(Math.random() * 255);
		var g = Math.floor(Math.random() * 255);
		var b = Math.floor(Math.random() * 255);
		return "rgba(" + r + "," + g + "," + b + ", 0.8)";
	}
	
	// Siswa Gender
	let siswa_bg = [];
	siswa_gender.map( () => {
		siswa_bg.push(dynamicColors());
	})
	
	var configChartSiswaGender = {
		type: 'pie',
		data: {
			datasets: [{
				data: siswa_gender,
				backgroundColor: siswa_bg,
			}],
			labels: ['Laki', 'Perempuan']
		},
		options: {
			responsive: false,
			// maintainAspectRatio: false,
			title: {
				display: true,
				text: '',
				fontSize: 14,
				lineHeight:3
			},
			plugins: {
			  legend: {
				display: true,
				position: 'bottom',
				fullWidth: false,
				labels: {
					padding: 10,
					boxWidth: 30
				}
			  },
			  title: {
				display: false,
				text: 'Gender Siswa'
			  }
			}
		}
	};
	
	/* Chart Siswa */
	var ctx = document.getElementById('chart-jenis-siswa').getContext('2d');
	window.chartSiswaGender = new Chart(ctx, configChartSiswaGender);
	
	let dataTablesListSiswa = '';
	let column = $.parseJSON($('#list-siswa-column').html());
	let url = base_url + 'dashboard/getDataDTListSiswa';
	
	const settings = {
		"processing": true,
		"serverSide": true,
		"scrollX": true,
		pageLength : 5,
		lengthChange: false,
		"ajax": {
			"url": url,
			"type": "POST"
		},
		"columns": column
	}
	
	let $add_setting = $('#list-siswa-setting');
	if ($add_setting.length > 0) {
		add_setting = $.parseJSON($('#list-siswa-setting').html());
		for (k in add_setting) {
			settings[k] = add_setting[k];
		}
	}
	
	dataTablesListSiswa =  $('#tabel-list-siswa').DataTable( settings );
});