<?php
require_once('app/includes/functions.php');
// echo '<pre>'; print_r($printer['dpi']); die;

$setting_kartu_default['kartu_width'] = 85.6;
$setting_kartu_default['kartu_height'] = 54;
$setting_kartu_default['foto_width'] = 20;
$setting_kartu_default['foto_height'] = 25;
$setting_kartu_default['data_depan_label_width'] = 11;
$setting_kartu_default['data_depan_show_label'] = 'Y';
$setting_kartu_default['data_depan_margin_left'] = 28;
$setting_kartu_default['data_depan_margin_top'] = 16.5;
$setting_kartu_default['data_depan_font_family'] = 'arial';
$setting_kartu_default['data_depan_font_size'] = 11;
$setting_kartu_default['data_depan_font_weight'] = 'bold';
$setting_kartu_default['data_depan_text_align'] = 'left';
$setting_kartu_default['data_depan_line_height'] = 15;
$setting_kartu_default['foto_margin_left'] = 5;
$setting_kartu_default['foto_margin_top'] = 16.5;
$setting_kartu_default['ttd_text_margin_left'] = 50;
$setting_kartu_default['ttd_text_margin_top'] = 35;
$setting_kartu_default['ttd_text_space_sign'] = 4;
$setting_kartu_default['ttd_text_font_family'] = 'arial';
$setting_kartu_default['ttd_text_align'] = 'left';
$setting_kartu_default['ttd_text_font_size'] = 10;
$setting_kartu_default['ttd_text_font_weight'] = 'bold';
$setting_kartu_default['ttd_sign_image'] = 'ttd_default.png';
$setting_kartu_default['ttd_sign_width'] = 15;
$setting_kartu_default['ttd_sign_height'] = 15;
$setting_kartu_default['ttd_sign_margin_left'] = 50;
$setting_kartu_default['ttd_sign_margin_top'] = 35;
$setting_kartu_default['ttd_cap_image'] = 'stempel_default.png';
$setting_kartu_default['ttd_cap_width'] = 15;
$setting_kartu_default['ttd_cap_height'] = 15;
$setting_kartu_default['ttd_cap_margin_left'] = 43;
$setting_kartu_default['ttd_cap_margin_top'] = 35;
$setting_kartu_default['berlaku_gunakan'] = 'Y';
$setting_kartu_default['berlaku_posisi'] = 'depan';
$setting_kartu_default['berlaku_jenis'] = 'custom_text';
$setting_kartu_default['berlaku_custom_text'] = 'Berlaku selama menjadi siswa';
$setting_kartu_default['berlaku_margin_left'] = 5;
$setting_kartu_default['berlaku_margin_top'] = 45;
$setting_kartu_default['berlaku_font_family'] = 'arial';
$setting_kartu_default['berlaku_font_size'] = 11;
$setting_kartu_default['berlaku_font_weight'] = 'bold';
$setting_kartu_default['qrcode_gunakan'] = 'Y';
$setting_kartu_default['qrcode_posisi'] = 'belakang';
$setting_kartu_default['qrcode_version'] = 4;
$setting_kartu_default['qrcode_ecc_level'] = 'L';
$setting_kartu_default['qrcode_size_module'] = '1.5';
$setting_kartu_default['qrcode_padding'] = '4px';
$setting_kartu_default['qrcode_content_jenis'] = 'global_text';
$setting_kartu_default['qrcode_content_global_text'] = 'Sample Text';
	
$setting_kartu_default['qrcode_margin_left'] = 63;
$setting_kartu_default['qrcode_margin_top'] = 30;
$setting_kartu_default['ttd_text'] = 'Semarang, 11 Juli {{YEAR}}
Rektor,

Agus Prawoto Hadi
NIP 1990092219921219002';
$setting_kartu_default['berlaku_periode_prefix'] = 'Berlaku s.d.';

if (empty($siswa_data_digunakan)) {
	$siswa_data_digunakan = [
		['pattern' => 'nama', 'judul_data' => 'Nama'],
		['pattern' => 'tempat_lahir, tgl_lahir', 'judul_data' => 'TTL'],
		['pattern' => 'alamat', 'judul_data' => 'Alamat'],
		['pattern' => 'nis', 'judul_data' => 'NIS'],
		['pattern' => 'nisn', 'judul_data' => 'NISN'],
	];
}

if (!$setting_kartu) {
	$setting_kartu = $setting_kartu_default;
} else {
	foreach ($setting_kartu as $name => &$val_setting) {
		if (!$val_setting || $val_setting == '0.0') {
			if (key_exists($name, $setting_kartu_default)) {
				$val_setting = $setting_kartu_default[$name];
			}
		}
	}
}

if ($setting_kartu['berlaku_hingga_tanggal'] == '' || $setting_kartu['berlaku_hingga_tanggal'] == '0000-00-00') {
	$setting_kartu['berlaku_hingga_tanggal'] = date('Y-m-d', strtotime('+4 years'));
}

$kartu_width = $setting_kartu['kartu_width'] * $printer['dpi'] / 25.4;
$kartu_height = $setting_kartu['kartu_height'] * $printer['dpi'] / 25.4;
/* $margin_left = $setting_kartu['kartu_margin_left'] * $printer['dpi'] / 25.4;
$margin_top = $setting_kartu['kartu_margin_top'] * $printer['dpi'] / 25.4;
$margin_right = $setting_kartu['kartu_margin_right'] * $printer['dpi'] / 25.4;
$margin_bottom = $setting_kartu['kartu_margin_bottom'] * $printer['dpi'] / 25.4; */

$margin_kartu_kanan = $margin_kartu_bawah = $margin_kartu_depan_belakang = 5;

$font_family_available = ['arial' => 'Arial', 'open sans' => 'Open Sans', 'Segoe UI' => 'Segoe UI', 'poppins' => 'Poppins'];

/* $margin_kiri = $printer['margin_kiri'] * $printer['dpi'] / 25.4;
$margin_atas = $printer['margin_atas'] * $printer['dpi'] / 25.4;
$margin_kartu_kanan = $printer['margin_kartu_kanan'] * $printer['dpi'] * $in;
$margin_kartu_bawah = $printer['margin_kartu_bawah'] * $printer['dpi'] * $in;
$margin_kartu_depan_belakang = $printer['margin_kartu_depan_belakang'] * $printer['dpi'] * $in; */

?>
<style id="style-foto-dimension">
	.kartu-foto img{
		<?php
		$foto_container_width = $setting_kartu['foto_width'] * $printer['dpi'] / 25.4;
		$foto_container_height = $setting_kartu['foto_height'] * $printer['dpi'] / 25.4;
		
		$size = image_dimension(BASE_PATH . 'public/images/foto/' . $siswa['foto'], $foto_container_width, $foto_container_height);

		if ( round($foto_container_width) == round($size[0]) ) {
			$style = 'width: 100%; height:auto';
		} else if ( round($foto_container_height) == round($size[1]) )  {
			$style = 'height: 100%;width: auto';
		} else {
			$style = 'width: auto; height:auto';
		}
		
		echo $style;
		?>
	}
</style>

<style id="style-data-label-width">
.kartu-detail .label{
	width:<?=$setting_kartu['data_depan_label_width'] * $printer['dpi'] / 25.4?>px;
}
</style>
<style id="style-data-depan-line-height">
.kartu-detail td {
	line-height: <?=$setting_kartu['data_depan_line_height']?>px;
}
</style>

<style id="style-data-depan-text-align">
.kartu-detail td {
	text-align: <?=$setting_kartu['data_depan_text_align']?>px;
}
</style>

<style>
.kartu-container {
	margin: 0;
	padding: 0;
}
.kartu-detail td {
	margin: 0;
	padding: 0;
	font-family: <?=$setting_kartu['data_depan_font_family']?>;
	font-size: <?=$setting_kartu['data_depan_font_size']?>px;
	font-weight: <?=$setting_kartu['data_depan_font_weight']?>;
	text-align: <?=$setting_kartu['data_depan_text_align']?>;
}
.panel-container {
	
	
}
.right-panel {
	width:100%;
	max-width: 750px;
	float: left;
}
.left-panel {
	max-width: <?= 120 * $printer['dpi'] / 25.4;?>px;
	margin-bottom: 20px;
	margin-right: 20px;
	float:left;
	text-align: center;
}
.kartu-page-container {
	display: inline-block;
}

.kartu-depan, .kartu-belakang {
	overflow: hidden;
	
}

.kartu-container {
	
}

.kartu-foto {
	width:<?=$setting_kartu['foto_width'] * $printer['dpi'] / 25.4?>px;
	height:<?=$setting_kartu['foto_height'] * $printer['dpi'] / 25.4?>px;
	left: <?=$setting_kartu['foto_margin_left'] * $printer['dpi'] / 25.4?>px;
    top: <?=$setting_kartu['foto_margin_top'] * $printer['dpi'] / 25.4?>px;
	position: absolute;
	border: 2px dashed #CCCCCC;
	background: #DDDDDD;
}

.kartu-detail {
	left: <?=$setting_kartu['data_depan_margin_left'] * $printer['dpi'] / 25.4?>px;
	top: <?=$setting_kartu['data_depan_margin_top'] * $printer['dpi'] / 25.4?>px;
	position: absolute;
}
.kartu-detail td {
	vertical-align: top;
}
.kartu-detail .separator {
	padding: 0 2px;
}
td.label {
	white-space: nowrap;
}
.kartu-content-container {
	position: relative;
	border:2px dashed #CCCCCC;
	width:<?=$kartu_width ?>px;
	height:<?= $kartu_height ?>px;
	background:url('<?= $config['base_url'] . $config['kartu_path'] . $setting_kartu['background_depan']?>'); 
	background-repeat: no-repeat; 
	background-size: 100% auto;
	background-position-y: -1px;
}

.kartu-belakang {
	margin-top: 20px;
	background:url('<?= $config['base_url'] . $config['kartu_path'] . $setting_kartu['background_belakang']?>'); 
	background-repeat: no-repeat; 
	background-size: 100% auto;
}
.kartu-tandatangan {
	position: absolute;
	top: <?=$setting_kartu['ttd_text_margin_top'] * $printer['dpi'] / 25.4?>px;
	left: <?=$setting_kartu['ttd_text_margin_left'] * $printer['dpi'] / 25.4?>px;
	text-align: <?=$setting_kartu['ttd_text_align']?>;
	font-size: <?=$setting_kartu['ttd_text_font_size']?>px;
	font-family: <?=$setting_kartu['ttd_text_font_family']?>;
	font-weight: <?=$setting_kartu['ttd_text_font_weight']?>;
}
.kartu-tandatangan p{
	margin:0;
	padding:0;
	line-height: normal;
	white-space: nowrap;
}

.kartu-tandatangan .jabatan {
	margin-bottom: <?=20 * $printer['dpi']/100?>px;
}
.scan-tandatangan {
	position: absolute;
    width: <?=50 * $printer['dpi']/100?>px;
    top: 10px;
    left: <?=40 * $printer['dpi']/100?>px;
}

.kartu-tandatangan-sign {
	position: absolute;
	width: <?=$setting_kartu['ttd_sign_width'] * $printer['dpi'] / 25.4?>px;
	height: <?=$setting_kartu['ttd_sign_height'] * $printer['dpi'] / 25.4?>px;
	top: <?=$setting_kartu['ttd_sign_margin_top'] * $printer['dpi'] / 25.4?>px;
	left: <?=$setting_kartu['ttd_sign_margin_left'] * $printer['dpi'] / 25.4?>px;
	border: 2px dashed #CCCCCC;
}
.kartu-tandatangan-sign img{
	<?php
	$ttd_sign_width = $setting_kartu['ttd_sign_width'] * $printer['dpi'] / 25.4;
	$ttd_sign_height = $setting_kartu['ttd_sign_height'] * $printer['dpi'] / 25.4;

	$size = image_dimension($config['kartu_path'] . $setting_kartu['ttd_sign_image'], $ttd_sign_width, $ttd_sign_height);

	if ( round($ttd_sign_width) == round($size[0]) ) {
		$style = 'width: 100%; height:auto';
	} else if ( round($ttd_sign_height) == round($size[1]) )  {
		$style = 'height: 100%;width: auto';
	} else {
		$style = 'width: auto; height:auto';
	}
	
	echo $style;
	?>
}

.kartu-tandatangan-cap {
	position: absolute;
	width: <?=$setting_kartu['ttd_cap_width'] * $printer['dpi'] / 25.4?>px;
	height: <?=$setting_kartu['ttd_cap_height'] * $printer['dpi'] / 25.4?>px;
	top: <?=$setting_kartu['ttd_cap_margin_top'] * $printer['dpi'] / 25.4?>px;
	left: <?=$setting_kartu['ttd_cap_margin_left'] * $printer['dpi'] / 25.4?>px;
	border: 2px dashed #CCCCCC;
	z-index: 5;
}
.kartu-tandatangan-cap img{
	<?php
		
	$ttd_cap_width = $setting_kartu['ttd_cap_width'] * $printer['dpi'] / 25.4;
	$ttd_cap_height = $setting_kartu['ttd_cap_height'] * $printer['dpi'] / 25.4;
	
	$size = image_dimension($config['kartu_path'] . $setting_kartu['ttd_cap_image'], $ttd_cap_width, $ttd_cap_height);

	if ( round($ttd_cap_width) == round($size[0]) ) {
		$style = 'width: 100%; height:auto';
	} else if ( round($ttd_cap_height) == round($size[1]) )  {
		$style = 'height: 100%;width: auto';
	} else {
		$style = 'width: auto; height:auto';
	}
	
	echo $style;
	?>
}

.cap-tandatangan {
	position: absolute;
    width: <?=70 * $printer['dpi']/100?>px;
    top: <?=-5 * $printer['dpi']/100?>px;
    left: <?=-30 * $printer['dpi']/100?>px;
}

.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

.scan-barcode {
	position: absolute;
    width: <?=30 * $printer['dpi']/100?>px;
    bottom: <?= 10 * $printer['dpi']/100?>px;
    left: <?=20 * $printer['dpi']/100?>px;
}
.berlaku-container {
	position: absolute;
    top: <?=$setting_kartu['berlaku_margin_top'] * $printer['dpi'] / 25.4;?>px;
    left: <?=$setting_kartu['berlaku_margin_left'] * $printer['dpi'] / 25.4;?>px;
	font-family: <?=$setting_kartu['berlaku_font_family']?>;
	font-size: <?=$setting_kartu['berlaku_font_size']?>px;
	font-weight: <?=$setting_kartu['berlaku_font_weight']?>;
}

@media screen and (max-width: 1024px) {
	.left-panel {
		float:none;
		margin-right: auto;
		float: none;
		margin-right: auto;
		margin-left: auto;
	}
}
</style>

<?php

function showTtd($setting_kartu, $printer, $show = true) {

	global $config;

	$ttd_text = str_replace('{{YEAR}}', date('Y'), $setting_kartu['ttd_text']);
	$exp = explode("\r\n", $ttd_text);
	
	$content = '';
	$space = false;
	foreach ($exp as $val) {
		$val = trim($val);
		if ($val){
			$content .= '<p>' . $val . '</p>';
		} else {
			if (!$space) {
				$content .= '<div id="ttd-text-space-sign" style="height:' . ($setting_kartu['ttd_text_space_sign'] * $printer['dpi'] / 25.4) . 'px;"></div>';
				$space = true;
			}
		}
	}
	
	$display = $show ? '' : ' style="display:none"';
	
	$html = '
	<div id="ttd-container"' . $display . '>
		<div class="kartu-tandatangan">' . $content . '</div>
		<div class="kartu-tandatangan-sign">
			<img id="ttd-sign-image" src="' . $config['base_url'] . 'public/images/kartu/' . $setting_kartu['ttd_sign_image'] . '"/>
			<input type="hidden" name="ttd_sign_image_old" value="' . $setting_kartu['ttd_sign_image'] . '"/>
		</div>
		<div class="kartu-tandatangan-cap">
			<img id="ttd-cap-image" src="' . $config['base_url'] . 'public/images/kartu/' . $setting_kartu['ttd_cap_image'] . '"/>
			<input type="hidden" name="ttd_cap_image_old" value="' . $setting_kartu['ttd_cap_image'] . '"/>
		</div>
	</div>';
	
	return $html;
}

function berlakuKartu($setting_kartu, $show = true) {
	$display = $show ? '' : ' style="display:none"';
	$html = '<div class="berlaku-container"' . $display . '>';
	if ($setting_kartu['berlaku_jenis'] == 'periode') {
		$exp = explode('-', $setting_kartu['berlaku_hingga_tanggal']);
		$html .= $setting_kartu['berlaku_periode_prefix'] . ' ' . $exp[2] . ' ' . nama_bulan( (int) $exp[1]) . ' ' . $exp[0];
	} else {
		$html .= $setting_kartu['berlaku_custom_text'];
	}
	$html .= '</div>';
	return $html;
}

function showQrcode($setting_kartu, $printer, $siswa, $show = true) {
	
	$display = $show ? '' : 'display:none;';
	$qrcode_margin_left = $setting_kartu['qrcode_margin_left'] * $printer['dpi'] / 25.4; 
	$qrcode_margin_top = $setting_kartu['qrcode_margin_top'] * $printer['dpi'] / 25.4; 
	$setting_kartu['qrcode_content'] = '<div class="qrcode-container" style="position:absolute;' . $display . 'z-index:6;top:'.$qrcode_margin_top.'px;left:'. $qrcode_margin_left .'px;padding:' . $setting_kartu['qrcode_padding'] . ';background:#FFFFFF">{{CONTENT}}</div>';
	
	if ($siswa['qrcode_text']) {
		$qrcode_text = $siswa['qrcode_text'];
	} else {
		if ($setting_kartu['qrcode_content_jenis'] == 'field_database') {
			$qrcode_text = $siswa[$setting_kartu['qrcode_content_field_database']] ;
			
		} else {
			$qrcode_text = $setting_kartu['qrcode_content_global_text'];
		}
	}	
	
	$qrcode_content = generateQRCode($setting_kartu['qrcode_version'], $setting_kartu['qrcode_ecc_level'], $qrcode_text, $setting_kartu['qrcode_size_module']);
	$setting_kartu['qrcode_content'] = str_replace('{{CONTENT}}', $qrcode_content, $setting_kartu['qrcode_content']);
	
	return $setting_kartu['qrcode_content'];
}
?>
<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<?php 
			include 'app/helpers/html_helper.php';
			echo btn_label(['attr' => ['class' => 'btn btn-success btn-xs'],
				'url' => module_url() . '?action=add',
				'icon' => 'fa fa-plus',
				'label' => 'Tambah Data'
			]);
			
			echo btn_label(['attr' => ['class' => 'btn btn-light btn-xs'],
				'url' => module_url(),
				'icon' => 'fa fa-arrow-circle-left',
				'label' => 'Daftar Kartu'
			]);
		?>
		<hr/>
		<?php
		
		if (!empty($msg)) {
			show_message($msg['content'], $msg['status']);
		}
		
		?>
		<div class="panel-container">
			<div class="left-panel">
				<div class="kartu-page-container">
					<div class="kartu-container">
						<div class="kartu-content-container kartu-depan">
							<div class="kartu-foto">
							<?php
								if ($siswa['foto'] && file_exists($config['foto_path'] . $siswa['foto'])) {
								?>
									<img src="<?=$config['base_url'] . $config['foto_path'] . $siswa['foto']?>"/>
							<?php } ?>
							</div>
							<div class="kartu-detail">
								<table cellspacing="0" cellpadding="0">
									<?php
									
									$fields_sorted = [];
									foreach ($fields as $name => $val) {
										$fields_sorted[$name] = strlen($name);
									}
									arsort($fields_sorted);
									
									foreach ($siswa_data_digunakan as $val_digunakan) {
										
										$data_siswa ='';
										foreach ($fields_sorted as $name => $value) {
											if(strpos ($val_digunakan['pattern'], $name) !== false) 
											{
												$value = $fields[$name];
												if ( strpos($name, 'tgl') !== false) {
													$exp = explode('-', $value);
													$value = $exp[2] . ' ' . nama_bulan( $exp[1] * 1 ) . ' ' . $exp[0];
												}
												$val_digunakan['pattern'] = str_replace($name, $value, $val_digunakan['pattern']);
											}
										}
										
										if ($setting_kartu['data_depan_show_label'] == 'Y') {
											echo '<tr>
												<td class="label">' . $val_digunakan['judul_data'] . '</td>
												<td class="separator">:</td>
												<td>' . $val_digunakan['pattern'] . '</td>
											</tr>';
										} else {
											echo '<tr>
												<td>' . $val_digunakan['pattern'] . '</td>
											</tr>';
										}
									}
									?>
								</table>

							</div>
							<?php
							
							if ($setting_kartu['ttd_gunakan'] ) {
								if ($setting_kartu['ttd_gunakan'] == 'Y' && $setting_kartu['ttd_posisi'] == 'depan') {	
									echo showTtd($setting_kartu, $printer);
								}
								
								if ($setting_kartu['ttd_gunakan'] == 'N') {
									echo showTtd($setting_kartu, $printer, false);
								}
							} else {
								echo showTtd($setting_kartu, $printer);
							}
							
							if ($setting_kartu['qrcode_gunakan'] == 'Y' && $setting_kartu['qrcode_posisi'] == 'depan') {
								echo showQrcode($setting_kartu, $printer, $siswa);
							}
													
							if ($setting_kartu['berlaku_gunakan'] == 'Y' && $setting_kartu['berlaku_posisi'] == 'depan') {
								echo berlakuKartu($setting_kartu);
							}
							
							if ($setting_kartu['berlaku_gunakan'] == 'N') {
								echo berlakuKartu($setting_kartu, false);
							}
							?>
							
						</div>
						
						<div class="kartu-content-container kartu-belakang">
							<?php
							if ($setting_kartu['ttd_gunakan'] == 'Y' && $setting_kartu['ttd_posisi'] == 'belakang') {
								echo showTtd($setting_kartu, $printer);
							}
							
							if ($setting_kartu['qrcode_gunakan'] == 'Y' && $setting_kartu['qrcode_posisi'] == 'belakang') {
								echo showQrcode($setting_kartu, $printer, $siswa);
							}
							
							if ($setting_kartu['qrcode_gunakan'] == 'N') {
								echo showQrcode($setting_kartu, $printer, $siswa, false);
							}
							
							if ($setting_kartu['berlaku_gunakan'] == 'Y' && $setting_kartu['berlaku_posisi'] == 'belakang') {
								echo berlakuKartu($setting_kartu);
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="right-panel">
				<div class="kartu-menu-container">
					<form method="post">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-tab-pane" type="button" role="tab" aria-controls="general-tab-pane" aria-selected="true">General</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="false">Kartu</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="data-siswa-tab" data-bs-toggle="tab" data-bs-target="#data-siswa-tab-pane" type="button" role="tab" aria-controls="data-siswa-tab-pane" aria-selected="false">Data Siswa</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="foto-tab" data-bs-toggle="tab" data-bs-target="#foto-tab-pane" type="button" role="tab" aria-controls="foto-tab-pane" aria-selected="false">Foto</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="ttd-tab" data-bs-toggle="tab" data-bs-target="#ttd-tab-pane" type="button" role="tab" aria-controls="ttd-tab-pane" aria-selected="false">Tanda Tangan</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="qrcode-tab" data-bs-toggle="tab" data-bs-target="#qrcode-tab-pane" type="button" role="tab" aria-controls="qrcode-tab-pane" aria-selected="false">QR Code</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="berlaku-tab" data-bs-toggle="tab" data-bs-target="#berlaku-tab-pane" type="button" role="tab" aria-controls="berlaku-tab-pane" aria-selected="false">Berlaku</button>
							</li>
						</ul>
						<div class="tab-content mt-3">
							<div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel" aria-labelledby="general-tab" tabindex="0">
								<div class="row mb-3">
									<label class="col-sm-3">Nama Setting</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="nama_setting" value="<?=@$setting_kartu['nama_setting']?>"/>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-3">Gunakan</label>
									<div class="col-sm-9">
										<?=options(['name' => 'gunakan', 'id' => 'gunakan-template'], ['Y' => 'Ya', 'N' => 'Tidak'], $setting_kartu['gunakan'])?>
										<small>Gunakan template ini</small>
									</div>
								</div>
							</div>
							<!-- 
								############## KARTU 
							-->
							<div class="tab-pane fade" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
								<div class="row mb-3">
									<label class="col-sm-3">Reset Ukuran</label>
									<div class="col-sm-9">
										<button type="button" class="btn btn-outline-secondary" id="kartu-reset-lanscape">Lanscape</button>
										<button type="button" class="btn btn-outline-secondary" id="kartu-reset-portrait">Portrait</button>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-3">Lebar</label>
									<div class="col-sm-9">
										<input type="range" value="<?=round($setting_kartu['kartu_width'])?>" class="form-range me-3" min="3" step="0.1" max="100" id="kartu-width-slider">
										<div class="input-group">
											<input type="number" step="0.1" class=" form-control" name="kartu_width" style="width:100px" id="kartu-width-input" value="<?=round($setting_kartu['kartu_width'], 1)?>" />
											<span class="input-group-text">mm</span>
										</div>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-3">Tinggi</label>
									<div class="col-sm-9">
										<input type="range" value="<?=round($setting_kartu['kartu_height'])?>" class="form-range me-3" min="3" step="0.1" max="100" id="kartu-height-slider">
										<div class="input-group">
											<input type="number" step="0.1" class="form-control"  name="kartu_height" style="width:100px" id="kartu-height-input" value="<?=round($setting_kartu['kartu_height'], 1)?>" />
											<span class="input-group-text">mm</span>
										</div>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-3">Background Depan</label>
									<div class="col-sm-9">
										<div class="input-group">
											<input type="file" name="background_depan" class="form-control file file-background-depan"/>
											<button type="button" class="btn btn-outline-secondary btn-reset btn-reset-background" data-posisi="depan" disabled>Cancel</button>
										</div>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-3">Background Belakang</label>
									<div class="col-sm-9">
										<div class="input-group">
											<input type="file" name="background_belakang" class="form-control file file-background-belakang"/>
											<button type="button" class="btn btn-outline-secondary btn-reset  btn-reset-background" data-posisi="belakang" disabled>Cancel</button>
										</div>
									</div>
								</div>
								
							</div>
							<!-- 
								############## DATA SISWA 
							-->
							<div class="tab-pane fade" id="data-siswa-tab-pane" style="max-width:750px" role="tabpanel" aria-labelledby="data-siswa-tab" tabindex="0">
								<ul class="nav nav-tabs" id="data-siswa-tab" role="tablist">
									<li class="nav-item">
										<button class="nav-link active" id="data-siswa-item-tab" data-bs-toggle="tab" data-bs-target="#data-siswa-item-tab-pane" type="button" role="tab" aria-controls="data-siswa-item-tab-pane" aria-selected="true">Data</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="data-siswa-layout-tab" data-bs-toggle="tab" data-bs-target="#data-siswa-layout-tab-pane" type="button" role="tab" aria-controls="data-siswa-layout-tab-pane" aria-selected="false">Layout</button>
									</li>
								</ul>
								<div class="tab-content mt-3">
									<div class="tab-pane fade show active" id="data-siswa-item-tab-pane" role="tabpanel" aria-labelledby="data-siswa-item-tab" tabindex="0">
										<div class="row mb-3">
											<div class="col-sm-9">
												<button type="button" id="add-data-depan" class="btn btn-outline-success btn-xs"><i class="fas fa-plus me-2"></i>Tambah Data</button>
											</div>
										</div>
										<div class="row mt-3">		
											<div>
												<div class="container-panel">
													<div id="list-data-item-container">
													<?php
													foreach ($siswa_data_digunakan as $val) 
													{
													?>
														<div class="card data-item-container shadow-sm mb-3">
															<ul class="toolbox">
																<li>
																	<div class="grip-handler"><i class="fas fa-grip-horizontal"></i></div>
																</li>
																<li>
																	<button class="bg-success btn-edit text-white small"><i class="fas fa-pencil-alt"></i></button>
																</li>
																<li>
																	<button class="bg-danger btn-delete text-white small"><i class="fas fa-times"></i></button>
																</li>
															</ul>
															<div class="body">
																<div class="row col-sm-12 item-label">
																	<?=$val['judul_data']?>
																</div>
															</div>
															<input type="hidden" class="pattern" name="pattern[]" value="<?=$val['pattern']?>"/>
															<input type="hidden" class="judul-data" name="judul_data[]" value="<?=$val['judul_data']?>"/>
														</div>
													<?php
													}?>
													<textarea style="display:none" class="siswa" id="siswa"><?=json_encode($siswa)?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="data-siswa-layout-tab-pane" role="tabpanel" aria-labelledby="data-siswa-layout-tab" tabindex="0">
										<div class="row mb-3">
											<label class="col-sm-3">Margin Left</label>
											<div class="col-sm-9">
												<input type="range" value="<?=$setting_kartu['data_depan_margin_left'] ?>" class="form-range" step="0.1" min="3" max="<?=$setting_kartu['kartu_width']/2?>" id="data-margin-left">
												<div class="d-flex">
													<div class="input-group">
														<input type="number" name="data_depan_margin_left" step="0.1" min="3" max="<?=$setting_kartu['kartu_width']/2?>" class=" form-control" style="width:100px" id="data-margin-left-input" value="<?=$setting_kartu['data_depan_margin_left']?>" />
														<span class="input-group-text">mm</span>
													</div>
													<button type="button" id="data-margin-left-center" class="ms-2 btn btn-outline-secondary btn-xs">Center</button>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label class="col-sm-3">Margin Top</label>
											<div class="col-sm-9">
												<input type="range" value="<?=$setting_kartu['data_depan_margin_top']?>" class="form-range" step="0.1" min="3" max="<?=$setting_kartu['kartu_height']?>" id="data-margin-top">
												<div class="input-group">
													<input type="number" name="data_depan_margin_top" step="0.1" min="3" max="<?=$setting_kartu['kartu_height']?>" class=" form-control" style="width:100px" id="data-margin-top-input" value="<?=$setting_kartu['data_depan_margin_top']?>" />
													<span class="input-group-text">mm</span>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label class="col-sm-3">Tampilkan Label</label>
											<div class="col-sm-9"><?=options(['name' => 'data_depan_show_label', 'id' => 'tampilkan-label'], ['Y' => 'Ya', 'N' => 'Tidak'], $setting_kartu['data_depan_show_label'])?></div>
										</div>
										<div class="row mb-3">
											<label class="col-sm-3">Lebar Label</label>
											<div class="col-sm-9">
												<input type="range" value="<?=$setting_kartu['data_depan_label_width']?>" class="form-range" min="10" max="40" id="data-depan-label-width-slider">
												<div class="input-group">
													<input type="number" name="data_depan_label_width" step="0.1" min="10" max="40" class=" form-control" style="width:100px" id="data-depan-label-width-input" value="<?=$setting_kartu['data_depan_label_width']?>" />
													<span class="input-group-text">mm</span>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label class="col-sm-3">Spasi Baris</label>
											<div class="col-sm-9">
												<input type="range" value="<?=$setting_kartu['data_depan_line_height']?>" class="form-range" step="0.5" min="10" max="40" id="data-depan-line-height-slider">
												<div class="input-group">
													<input type="number" name="data_depan_line_height" step="0.1" min="10" max="40" class=" form-control" style="width:100px" id="data-depan-line-height-input" value="<?=$setting_kartu['data_depan_line_height']?>" />
													<span class="input-group-text">px</span>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label class="col-sm-3">Font Family</label>
											<div class="col-sm-9"><?=options(['name' => 'data_depan_font_family', 'id' => 'data-font-family'], $font_family_available, $setting_kartu['data_depan_font_family'])?></div>
										</div>
										<div class="row mb-3">
											<label class="col-sm-3">Font Size</label>
											<div class="col-sm-9">
												<input type="range" value="<?=$setting_kartu['data_depan_font_size']?>" step="0.5" class="form-range me-3" min="7" max="25" id="data-depan-font-size-slider">
												<div class="input-group">
													<input type="number"name="data_depan_font_size"  step="0.1" min="3" value="<?=$setting_kartu['data_depan_font_size']?>" max="25" class=" form-control" style="width:100px" id="data-depan-font-size-input" value="<?=$setting_kartu['data_depan_font_size']?>" />
													<span class="input-group-text">px</span>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label class="col-sm-3">Text Align</label>
											<div class="col-sm-9">
												<div class="input-group">
													<?php
														$align = ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'];
														
														foreach ($align as $name => $val) {
															$add_class = $setting_kartu['data_depan_text_align'] == $name ? 'ttd-text-align-selected' : '';
															$btn_class = $setting_kartu['data_depan_text_align'] == $name ? 'btn-secondary ' : 'btn-outline-secondary ';
															echo '<button type="button" class="btn ' . $btn_class . $add_class . ' btn-data-depan-text-align" value="' . $name . '" data-align="' . $name . '">' . $val . '</button>';
														}
													?>
												</div>
												<input type="hidden" name="data_depan_text_align" value="<?=$setting_kartu['data_depan_text_align']?>"/>
											</div>
										</div>
										<div class="row mb-3">
											<label class="col-sm-3">Font Weight</label>
											<div class="col-sm-9">
												<div class="input-group">
													<?php
														$font_weight = ['normal' => 'Normal', 'bold' => 'Bold'];
														
														foreach ($font_weight as $name => $val) {
															$add_class = $setting_kartu['data_depan_font_weight'] == $name ? 'ttd-text-align-selected' : '';
															$btn_class = $setting_kartu['data_depan_font_weight'] == $name ? 'btn-secondary ' : 'btn-outline-secondary ';
															echo '<button type="button" class="btn ' . $btn_class . $add_class . ' btn-data-depan-fw" value="' . $name . '" data-align="' . $name . '">' . $val . '</button>';
														}
													?>
												</div>
												<input type="hidden" name="data_depan_font_weight" value="<?=$setting_kartu['data_depan_font_weight']?>"/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- 
								############## FOTO 
							-->
							<div class="tab-pane fade" id="foto-tab-pane" role="tabpanel" aria-labelledby="foto-tab" tabindex="0">
								<div class="row mb-3">
									<label class="col-sm-3">Lebar Maksimal</label>
									<div class="col-sm-9">
										<input type="range" value="<?=$setting_kartu['foto_width']?>" class="form-range me-3" min="15" step="0.1" max="<?=$setting_kartu['kartu_width']?>" id="foto-width-slider">
										<div class="input-group">
											<input type="number" step="0.1" class=" form-control" name="foto_width" style="width:100px" id="foto-width-input" value="<?=$setting_kartu['foto_width']?>" />
											<span class="input-group-text">mm</span>
										</div>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-3">Tinggi Maksimal</label>
									<div class="col-sm-9">
										<input type="range" value="<?=$setting_kartu['foto_height']?>" class="form-range me-3" min="15" step="0.1" max="<?=$setting_kartu['kartu_height']?>" id="foto-height-slider">
										<div class="input-group">
											<input type="number" step="0.1" class=" form-control" name="foto_height" style="width:100px" id="foto-height-input" value="<?=$setting_kartu['foto_height']?>" />
											<span class="input-group-text">mm</span>
										</div>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-3">Margin Kiri</label>
									<div class="col-sm-9">
										<input type="range" value="<?=$setting_kartu['foto_margin_left']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_width']?>" id="foto-margin-left-slider">
										<div class="d-flex">
											<div class="input-group">
												<input type="number" step="0.1" class=" form-control" name="foto_margin_left" style="width:100px" id="foto-margin-left-input" value="<?=$setting_kartu['foto_margin_left']?>" />
												<span class="input-group-text">mm</span>
											</div>
											<button type="button" id="foto-margin-left-center" class="ms-2 btn btn-outline-secondary btn-xs">Center</button>
										</div>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-3">Margin Atas</label>
									<div class="col-sm-9">
										<input type="range" value="<?=$setting_kartu['foto_margin_top']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_height']?>" id="foto-margin-top-slider">
										<div class="d-flex">
											<div class="input-group">
												<input type="number" step="0.1" class=" form-control" name="foto_margin_top" style="width:100px" id="foto-margin-top-input" value="<?=$setting_kartu['foto_margin_top']?>" />
												<span class="input-group-text">mm</span>
											</div>
											<button type="button" id="foto-margin-top-center" class="ms-2 btn btn-outline-secondary btn-xs">Center</button>
										</div>
									</div>
								</div>
							</div>
							
							<!-- 
								############## TANDA TANGAN 
							-->
							<div class="tab-pane fade" id="ttd-tab-pane" role="tabpanel" aria-labelledby="ttd-tab" tabindex="0">
								<div class="row mb-3">
									<label class="col-sm-3">Gunakan</label>
									<div class="col-sm-9"><?=options(['name' => 'ttd_gunakan', 'id' => 'ttd-gunakan'], ['Y' => 'Ya', 'N' => 'Tidak'], $setting_kartu['ttd_gunakan'])?></div>
								</div>
								<?php
									$display = $setting_kartu['ttd_gunakan'] == 'N' ? ' style="display:none"' : '';
								?>
								<div id="tanda-tangan-setting-container"<?=$display?>>
									<div class="row mb-3">
										<label class="col-sm-3">Posisi</label>
										<div class="col-sm-9"><?=options(['name' => 'ttd_posisi', 'id' => 'ttd-posisi'], ['depan' => 'Depan', 'belakang' => 'Belakang'], $setting_kartu['ttd_posisi'])?></div>
									</div>
									<ul class="nav nav-tabs" id="tada-tangan-tab" role="tablist">
										<li class="nav-item">
											<button class="nav-link active" id="ttd-teks-tab" data-bs-toggle="tab" data-bs-target="#ttd-teks-tab-pane" type="button" role="tab" aria-controls="ttd-teks-tab-pane" aria-selected="true">Teks</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="ttd-sign-tab" data-bs-toggle="tab" data-bs-target="#ttd-sign-tab-pane" type="button" role="tab" aria-controls="ttd-sign-tab-pane" aria-selected="false">Tanda Tangan</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="ttd-stempel-tab" data-bs-toggle="tab" data-bs-target="#ttd-stempel-tab-pane" type="button" role="tab" aria-controls="ttd-stempel-tab-pane" aria-selected="false">Stempel</button>
										</li>
									</ul>
									<!-- 
										############## TANDA TANGAN TEKS
									-->
									<div class="tab-content mt-3">
										<div class="tab-pane fade show active" id="ttd-teks-tab-pane" role="tabpanel" aria-labelledby="ttd-teks-tab" tabindex="0">
											<div class="row mb-3">
												<label class="col-sm-3">Konten</label>
												<div class="col-sm-9">
													<button class="btn btn-outline-success btn-edit-ttd-text btn-xs"><i class="fas fa-edit me-2"></i>Edit</button>
													<textarea style="display:none" name="ttd_text" id="ttd-text" class="form-control"><?=trim($setting_kartu['ttd_text'])?></textarea>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Margin Kiri</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_text_margin_left']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_width']?>" id="ttd-text-margin-left-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" step="0.1" class="form-control" name="ttd_text_margin_left" style="width:100px" id="ttd-text-margin-left-input" value="<?=$setting_kartu['ttd_text_margin_left']?>" />
															<span class="input-group-text">mm</span>
														</div>
														<button type="button" id="ttd-text-margin-left-center" class="ms-2 btn btn-outline-secondary btn-xs">Center</button>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Margin Atas</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_text_margin_top']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_height']?>" id="ttd-text-margin-top-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" step="0.1" class=" form-control" name="ttd_text_margin_top" style="width:100px" id="ttd-text-margin-top-input" value="<?=$setting_kartu['ttd_text_margin_top']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Spasi Tanda Tangan</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_text_space_sign']?>" class="form-range me-3" min="3" step="0.1" max="10" id="ttd-text-space-sign-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" step="0.1" class="form-control" name="ttd_text_space_sign" style="width:100px" id="ttd-text-space-sign-input" value="<?=$setting_kartu['ttd_text_space_sign']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Text Align</label>
												<div class="col-sm-9">
													<div class="input-group">
														<?php
															$align = ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'];
															
															foreach ($align as $name => $val) {
																$add_class = $setting_kartu['ttd_text_align'] == $name ? 'ttd-text-align-selected' : '';
																$btn_class = $setting_kartu['ttd_text_align'] == $name ? 'btn-secondary ' : 'btn-outline-secondary ';
																echo '<button type="button" class="btn ' . $btn_class . $add_class . ' btn-ttd-text-align" value="' . $name . '" data-align="' . $name . '">' . $val . '</button>';
															}
														?>
													</div>
													<input type="hidden" name="ttd_text_align" value="<?=$setting_kartu['ttd_text_align']?>"/>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Font Family</label>
												<div class="col-sm-9"><?=options(['name' => 'ttd_text_font_family', 'id' => 'ttd-text-font-family'], $font_family_available, $setting_kartu['ttd_text_font_family'])?></div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Font Size</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_text_font_size']?>" step="0.5" class="form-range me-3" min="7" max="15" id="ttd-text-font-size-slider">
													<div class="input-group">
														<input type="number" name="ttd_text_font_size" step="0.1" min="7" value="<?=$setting_kartu['ttd_text_font_size']?>" max="15" class=" form-control" style="width:100px" id="ttd-text-font-size-input"/>
														<span class="input-group-text">px</span>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Font Weight</label>
												<div class="col-sm-9">
													<div class="input-group">
														<?php
															$font_weight = ['normal' => 'Normal', 'bold' => 'Bold'];
															
															foreach ($font_weight as $name => $val) {
																$add_class = $setting_kartu['ttd_text_font_weight'] == $name ? 'ttd-text-align-selected' : '';
																$btn_class = $setting_kartu['ttd_text_font_weight'] == $name ? 'btn-secondary ' : 'btn-outline-secondary ';
																echo '<button type="button" class="btn ' . $btn_class . $add_class . ' btn-ttd-text-fw" value="' . $name . '" data-align="' . $name . '">' . $val . '</button>';
															}
														?>
													</div>
													<input type="hidden" name="ttd_text_font_weight" value="<?=$setting_kartu['ttd_text_font_weight']?>"/>
												</div>
											</div>
										</div>
										<!-- 
											###### TANDA TANGAN SIGN
										-->
										<div class="tab-pane fade" id="ttd-sign-tab-pane" role="tabpanel" aria-labelledby="ttd-sign-tab" tabindex="0">
											<div class="row mb-3">
												<label class="col-sm-3">Image</label>
												<div class="col-sm-9">
													<div class="input-group">
														<input type="file" name="ttd_sign_image" class="form-control file file-ttd-sign-image"/>
														<button type="button" class="btn btn-outline-secondary btn-reset btn-reset-ttd-sign-image" data-posisi="depan" disabled>Cancel</button>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Lebar Maksimal</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_sign_width']?>" class="form-range me-3" min="10" step="0.1" max="40" id="ttd-sign-width-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" name="ttd_sign_width" step="0.1" class=" form-control" style="width:100px" id="ttd-sign-width-input" value="<?=$setting_kartu['ttd_sign_width']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Tinggi Maksimal</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_sign_height']?>" class="form-range me-3" min="0" step="0.1" max="40" id="ttd-sign-height-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" name="ttd_sign_height" step="0.1" class=" form-control" style="width:100px" id="ttd-sign-height-input" value="<?=$setting_kartu['ttd_sign_height']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Margin Kiri</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_sign_margin_left']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_width']?>" id="ttd-sign-margin-left-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" name="ttd_sign_margin_left" step="0.1" class=" form-control" style="width:100px" id="ttd-sign-margin-left-input" value="<?=$setting_kartu['ttd_sign_margin_left']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Margin Atas</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_sign_margin_top']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_height']?>" id="ttd-sign-margin-top-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" name="ttd_sign_margin_top" step="0.1" class=" form-control" style="width:100px" id="ttd-sign-margin-top-input" value="<?=$setting_kartu['ttd_sign_margin_top']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- 
											###### TANDA TANGAN STEMPEL
										-->
										<div class="tab-pane fade" id="ttd-stempel-tab-pane" role="tabpanel" aria-labelledby="ttd-stempel-tab" tabindex="0">
											<div class="row mb-3">
												<label class="col-sm-3">Image</label>
												<div class="col-sm-9">
													<div class="input-group">
														<input type="file" name="ttd_cap_image" class="form-control file file-ttd-cap-image"/>
														<button type="button" class="btn btn-outline-secondary btn-reset btn-reset-ttd-cap-image" data-posisi="depan" disabled>Cancel</button>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Lebar Maksimal</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_cap_width']?>" class="form-range me-3" min="10" step="0.1" max="40" id="ttd-cap-width-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" name="ttd_cap_width" step="0.1" class=" form-control" style="width:100px" id="ttd-cap-width-input" value="<?=$setting_kartu['ttd_cap_width']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Tinggi Maksimal</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_cap_height']?>" class="form-range me-3" min="0" step="0.1" max="40" id="ttd-cap-height-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" name="ttd_cap_height" step="0.1" class=" form-control" style="width:100px" id="ttd-cap-height-input" value="<?=$setting_kartu['ttd_cap_height']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Margin Kiri</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_cap_margin_left']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_width']?>" id="ttd-cap-margin-left-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" name="ttd_cap_margin_left" step="0.1" class=" form-control" style="width:100px" id="ttd-cap-margin-left-input" value="<?=$setting_kartu['ttd_cap_margin_left']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<label class="col-sm-3">Margin Atas</label>
												<div class="col-sm-9">
													<input type="range" value="<?=$setting_kartu['ttd_cap_margin_top']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_height']?>" id="ttd-cap-margin-top-slider">
													<div class="d-flex">
														<div class="input-group">
															<input type="number" name="ttd_cap_margin_top" step="0.1" class=" form-control" style="width:100px" id="ttd-cap-margin-top-input" value="<?=$setting_kartu['ttd_cap_margin_top']?>" />
															<span class="input-group-text">mm</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--
							 ########## QRCode
							-->
							<div class="tab-pane fade" id="qrcode-tab-pane" role="tabpanel" aria-labelledby="qrcode-tab" tabindex="0">
								<div class="row mb-3">
									<label class="col-sm-3">Gunakan</label>
									<div class="col-sm-9"><?=options(['name' => 'qrcode_gunakan', 'id' => 'qrcode-gunakan'], ['Y' => 'Ya', 'N' => 'Tidak'], $setting_kartu['qrcode_gunakan'])?></div>
								</div>
								<?php
								$display = $setting_kartu['qrcode_gunakan'] == 'Y' ? '' : ' style="display:none"';
								?>
								<div id="qrcode-container-setting"<?=$display?>>
									<div class="row mb-3">
										<label class="col-sm-3">Posisi</label>
										<div class="col-sm-9"><?=options(['name' => 'qrcode_posisi', 'id' => 'qrcode-posisi'], ['depan' => 'Depan', 'belakang' => 'Belakang'], $setting_kartu['qrcode_posisi'], $setting_kartu['qrcode_posisi'])?></div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Margin Kiri</label>
										<div class="col-sm-9">
											<input type="range" value="<?=$setting_kartu['qrcode_margin_left']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_width']?>" id="qrcode-margin-left-slider">
											<div class="d-flex">
												<div class="input-group">
													<input type="number" name="qrcode_margin_left" step="0.1" class=" form-control" style="width:100px" id="qrcode-margin-left-input" value="<?=$setting_kartu['qrcode_margin_left']?>" />
													<span class="input-group-text">mm</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Margin Atas</label>
										<div class="col-sm-9">
											<input type="range" value="<?=$setting_kartu['qrcode_margin_top']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_height']?>" id="qrcode-margin-top-slider">
											<div class="d-flex">
												<div class="input-group">
													<input type="number" name="qrcode_margin_top" step="0.1" class=" form-control" style="width:100px" id="qrcode-margin-top-input" value="<?=$setting_kartu['qrcode_margin_top']?>" />
													<span class="input-group-text">mm</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">QR Code Version</label>
										<div class="col-sm-9">
											<div class="input-group">
												<?php
												$list = range(0,40);
												unset($list[0]);
												echo options(['name' => 'qrcode_version', 'id' => 'qrcode-version'], $list, $setting_kartu['qrcode_version']);
												?>
												<button type="button" data-info="<strong>QRCode Version</strong><hr/>Pilih version 1 s.d 40. Versi menentukan jumlah karakter yang ditampung, misal dengan error correction (<strong>ECC</strong>) level 7%, version 1 dapat menampung 25 karakter alphanumeric, 2:47, 3:77, 4:114. Semakin besar version semakin besar ukuran QRCode, jadi gunakan versi sesuai kebutuhan" class="btn btn-secondary show-info"><i class="fas fa-info-circle"></i></button>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">ECC Level</label>
										<div class="col-sm-9">
											<div class="input-group">
												<?php
												echo options(['name' => 'qrcode_ecc_level', 'id' => 'qrcode-ecc-level'], ['L' => 'L | Low (7%)', 'M' => 'M | Medium (15%)', 'Q' => 'Q | Quality (25%)', 'H' => 'H | High (30%)'], $setting_kartu['qrcode_ecc_level']);
												?>
												<button type="button" data-info="<strong>ECC Level</strong><hr/>Error Correction Capability. ECC digunakan agar qrcode tetap dapat terbaca meskipun rusak, L untuk paling rendah dan H untuk yang paling tinggi, semakin tinggi ECC semakin besar ukuran QR Code. Di aplikasi ini, karena qrcode berupa kode HTML, maka kita dapat menggunakan ECC Level L, sehingga karakter yang ditampung menjadi lebih banyak" class="btn btn-secondary show-info"><i class="fas fa-info-circle"></i></button>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Size Pixel</label>
										<div class="col-sm-9">
											<div class="input-group">
												<?php
												echo options(['name' => 'qrcode_size_module', 'id' => 'qrcode-size-pixel'], ['0.5' => '0.5px', '1' => '1px', '1.5' => '1.5px', '2' => '2px', '2.5' => '2.5px', '3' => '3px'], $setting_kartu['qrcode_size_module']);
												?>
												<button type="button" data-info="<strong>Size Pixel</strong><hr/>Ukuran width (lebar) tiap tiap dot pada QR Code. Ukuran yang terlalu kecil menyebabkan QR Code sulit terbaca" class="btn btn-secondary show-info"><i class="fas fa-info-circle"></i></button>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Padding Tepi</label>
										<div class="col-sm-9">
											<?php
											echo options(['name' => 'qrcode_padding', 'id' => 'qrcode-padding'], ['1px' => '1px', '2px' => '2px', '3px' => '3px', '4px' => '4px', '5px' => '5px'
											,'6px' => '6px', '7px' => '7px', '8px' => '8px', '9px' => '9px', '10px' => '10px'], $setting_kartu['qrcode_padding']);
											?>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Content</label>
										<div class="col-sm-9">
											<?php
												if (empty($setting_kartu['qrcode_content_jenis'])) {
													$setting_kartu['qrcode_content_jenis'] = 'field_database';
												}
												$content_jenis = $setting_kartu['qrcode_content_jenis'];
												echo options(['name' => 'qrcode_content_jenis', 'id' => 'qrcode-content-jenis'], ['field_database' => 'Kolom Tabel Siswa', 'global_text' => 'Global Text'], $content_jenis);
												
												$display = $content_jenis == 'field_database' ? '' : 'display:none'; 
												echo options(['name' => 'qrcode_content_field_database', 'id' => 'qrcode-content-field-database', 'class' => 'mt-2', 'style' => $display], $field_table, $setting_kartu['qrcode_content_field_database']);
												
												$display = $content_jenis == 'global_text' ? '' : 'display:none'; 
											?>
											<div id="qrcode-content-global-text" style="<?=$display?>">
												<textarea class="form-control mt-2" name="qrcode_content_global_text"><?=$setting_kartu['qrcode_content_global_text']?></textarea>
												<button type="button" id="btn-preview-qrcode" class="btn btn-outline-secondary mt-2">Preview</button>
												<small class="form-text text-muted">Text QR Code akan digunakan di semua kartu</small>
												
											</div>
										</div>
									</div>
									
								</div>
							</div>
							<!--
							 ########## Berlaku
							-->
							<div class="tab-pane fade" id="berlaku-tab-pane" role="tabpanel" aria-labelledby="berlaku-tab" tabindex="0">
								<div class="row mb-3">
									<label class="col-sm-3">Gunakan</label>
									<div class="col-sm-9"><?=options(['name' => 'berlaku_gunakan', 'id' => 'berlaku-gunakan'], ['Y' => 'Ya', 'N' => 'Tidak'], $setting_kartu['berlaku_gunakan'])?></div>
								</div>
								<?php
								$display = $setting_kartu['berlaku_gunakan'] == 'Y' ? '' : ' style="display:none"';
								?>
								<div id="berlaku-container-setting"<?=$display?>>
									<div class="row mb-3">
										<label class="col-sm-3">Posisi</label>
										<div class="col-sm-9"><?=options(['name' => 'berlaku_posisi', 'id' => 'berlaku-posisi'], ['depan' => 'Depan', 'belakang' => 'Belakang'], $setting_kartu['berlaku_posisi'], $setting_kartu['berlaku_posisi'])?></div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Margin Kiri</label>
										<div class="col-sm-9">
											<input type="range" value="<?=$setting_kartu['berlaku_margin_left']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_width']?>" id="berlaku-margin-left-slider">
											<div class="d-flex">
												<div class="input-group">
													<input type="number" name="berlaku_margin_left" step="0.1" class=" form-control" style="width:100px" id="berlaku-margin-left-input" value="<?=$setting_kartu['berlaku_margin_left']?>" />
													<span class="input-group-text">mm</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Margin Atas</label>
										<div class="col-sm-9">
											<input type="range" value="<?=$setting_kartu['berlaku_margin_top']?>" class="form-range me-3" min="0" step="0.1" max="<?=$setting_kartu['kartu_height']?>" id="berlaku-margin-top-slider">
											<div class="d-flex">
												<div class="input-group">
													<input type="number" name="berlaku_margin_top" step="0.1" class=" form-control" style="width:100px" id="berlaku-margin-top-input" value="<?=$setting_kartu['berlaku_margin_top']?>" />
													<span class="input-group-text">mm</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Font Family</label>
										<div class="col-sm-9"><?=options(['name' => 'berlaku_font_family', 'id' => 'berlaku-font-family'], $font_family_available, $setting_kartu['berlaku_font_family'])?></div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Font Size</label>
										<div class="col-sm-9">
											<input type="range" value="<?=$setting_kartu['berlaku_font_size']?>" step="0.5" class="form-range me-3" min="7" max="25" id="berlaku-font-size-slider">
											<div class="input-group">
												<input type="number"name="berlaku_font_size"  step="0.1" min="3" value="<?=$setting_kartu['berlaku_font_size']?>" max="25" class=" form-control" style="width:100px" id="berlaku-font-size-input" />
												<span class="input-group-text">px</span>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Font Weight</label>
										<div class="col-sm-9">
											<div class="input-group">
												<?php
													$font_weight = ['normal' => 'Normal', 'bold' => 'Bold'];
													
													foreach ($font_weight as $name => $val) {
														$add_class = $setting_kartu['berlaku_font_weight'] == $name ? 'ttd-text-align-selected' : '';
														$btn_class = $setting_kartu['berlaku_font_weight'] == $name ? 'btn-secondary ' : 'btn-outline-secondary ';
														echo '<button type="button" class="btn ' . $btn_class . $add_class . ' btn-berlaku-fw" value="' . $name . '" data-align="' . $name . '">' . $val . '</button>';
													}
												?>
											</div>
											<input type="hidden" name="berlaku_font_weight" value="<?=$setting_kartu['berlaku_font_weight']?>"/>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-sm-3">Masa Berlaku</label>
										<div class="col-sm-9">
											<?php 
											echo options(['name' => 'berlaku_jenis', 'id' => 'berlaku-jenis'], ['custom_text' => "Custom Teks", 'periode' => 'Periode'], set_value('berlaku_jenis', $setting_kartu['berlaku_jenis']));
											
											$display = $setting_kartu['berlaku_jenis'] == 'periode' ? '' : ' style="display:none"';
											
											$exp = explode('-', $setting_kartu['berlaku_hingga_tanggal']);
											$berlaku_hingga_tanggal = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
											?>
											<div id="periode" class="mt-2" <?=$display?>>
												<div class="input-group">
													<input type="text" class="form-control" name="berlaku_periode_prefix" id="berlaku-periode-prefix" value="<?=$setting_kartu['berlaku_periode_prefix']?>"/> 
													<input type="text" name="berlaku_hingga_tanggal" id="berlaku-tanggal" value="<?=$berlaku_hingga_tanggal?>" class="form-control flatpickr" placeholder="Tanggal Berlaku"/>
												</div>
											</div>
											<?php 
											
											$display = $setting_kartu['berlaku_jenis'] == 'periode' ? ' style="display:none"' : '
											';
											?>
											<input id="custom-text" name="berlaku_custom_text" type="text" <?=$display?> class="form-control mt-2" value="<?=@$setting_kartu['berlaku_custom_text']?>"/>
										</div>
									</div>
								</div>
							</div>
						</div><!-- END TAB -->
						<button type="input" class="btn btn-primary mt-2" name="submit" id="submit">Submit</button>
						<input type="hidden" name="id" id="id" value="<?=@$_GET['id']?>"/>
					</form>
				</div>
			</div><!-- right panel -->
		</div>
	</div>
</div>

<textarea style="display:none" id="printer"><?=json_encode($printer)?></textarea>
<textarea style="display:none" id="setting-kartu"><?=json_encode($setting_kartu)?></textarea>

<!-- FROM JAVASCRIPT -->
<div id="form-data-item" style="display:none">
	<div class="row mb-3">
		<label class="col-sm-3">Label</label>
		<div class="col-sm-8">
			<input class="form-control form-data-label" value=""/>
		</div>
	</div>
	<div class="row">
		<label class="col-sm-3">Konten</label>
		<div class="col-sm-8">
			<input class="form-control form-data-content" value=""/>
			<?php
			foreach ($siswa as $column => $val) {
				echo '<button class="btn btn-outline-secondary btn-xs btn-add-data-item-column mt-2 me-2" value="' . $column . '">' . $column . '</button>';
			}
			
			?>
		</div>
	</div>
</div>