<html !DOCTYPE="HTML">

<head>
	<title>
		<?php
		global $current_module;
		global $js;
		global $styles;
		global $app_layout;
		global $setting_app;
		echo $current_module['judul_module'] . ' | ' . $setting_app['judul_web'] ?>
	</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?= $config['base_url'] . 'public/images/favicon.png' ?>" />
	<meta name="description" content="<?= $current_module['deskripsi'] ?>">
	<link rel="shortcut icon" href="<?= $config['images_url'] . 'favicon.png"' ?> />
<?php
require_once('app/helpers/html_helper.php');
require_once('app/includes/functions.php');
$inchi = 25.4;

$panjang = $setting_kartu['kartu_width'] * $printer['dpi'] / $inchi;
$lebar =  $setting_kartu['kartu_height'] * $printer['dpi'] / $inchi;
$margin_kiri = $printer['margin_left'] * $printer['dpi'] / $inchi;
$margin_atas = $printer['margin_top'] * $printer['dpi'] / $inchi;
$margin_kartu_kanan = $printer['margin_kartu_right'] * $printer['dpi'] / $inchi;
$margin_kartu_bawah = $printer['margin_kartu_bottom'] * $printer['dpi'] / $inchi;
$margin_kartu_depan_belakang = $printer['margin_kartu_depan_belakang'] * $printer['dpi'] / $inchi;

$kartu_width = $setting_kartu['kartu_width'] * $printer['dpi'] / 25.4;
$kartu_height = $setting_kartu['kartu_height'] * $printer['dpi'] / 25.4;
?>
<style>
body, html {
	margin: 0;
	padding: 0;
	font-family: arial;
	font-size: <?= (9.5 * $printer['dpi'] / 100) ?>px;
	font-weight: bold;
	color: #616161;
}

.cetak-container {
	padding-top: <?= $margin_atas ?>px;
	padding-left: <?= $margin_kiri ?>px;
	max-width:<?= $margin_kiri + (2 * $panjang) + $margin_kartu_kanan + 100 ?>px;
}

.kartu-container {
	margin:0;
	margin-right: <?= $margin_kartu_kanan ?>px;
	margin-bottom: <?= $margin_kartu_bawah ?>px;
	float:left;
	width:<?= $panjang ?>px;
}

.kartu-detail td {
	margin: 0;
	padding: 0;
	font-family: <?= $setting_kartu['data_depan_font_family'] ?>;
	font-size: <?= $setting_kartu['data_depan_font_size'] ?>px;
	font-weight: <?= $setting_kartu['data_depan_font_weight'] ?>;
	text-align: <?= $setting_kartu['data_depan_text_align'] ?>;
	line-height: <?= $setting_kartu['data_depan_line_height'] ?>px;
}

.kartu-detail .label{
	width:<?= $setting_kartu['data_depan_label_width'] * $printer['dpi'] / 25.4 ?>px;
}

.kartu-depan, .kartu-belakang {
	overflow: hidden;
}

.kartu-foto {
	width:<?= $setting_kartu['foto_width'] * $printer['dpi'] / 25.4 ?>px;
	height:<?= $setting_kartu['foto_height'] * $printer['dpi'] / 25.4 ?>px;
	left: <?= $setting_kartu['foto_margin_left'] * $printer['dpi'] / 25.4 ?>px;
    top: <?= $setting_kartu['foto_margin_top'] * $printer['dpi'] / 25.4 ?>px;
	position: absolute;
}

.kartu-foto img
{
	width: inherit;
	max-width: <?= $setting_kartu['foto_width'] * $printer['dpi'] / 25.4; ?>px;
	max-height: <?= $foto_container_height = $setting_kartu['foto_height'] * $printer['dpi'] / 25.4; ?>px;
}

.kartu-detail {
	left: <?= $setting_kartu['data_depan_margin_left'] * $printer['dpi'] / 25.4 ?>px;
	top: <?= $setting_kartu['data_depan_margin_top'] * $printer['dpi'] / 25.4 ?>px;
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

.text-0 td {
	color: #e4e6eb;
	font-size: 21px;
	width: 158px;
}

.text-1 td {
	color: #e4e6eb;
	font-size: 12px;
	font-weight: 500;
}

.text-2 td {
	color: #e4e6eb;
	font-size: 17px;
}

.kartu-content-container {
	position: relative;
	width:<?= $kartu_width ?>px;
	height:<?= $kartu_height ?>px;
	background-repeat: no-repeat; 
	background-size: 100% auto;
}

.kartu-depan {
	background-image:url('<?= $config['base_url'] . $config['kartu_path'] . $setting_kartu['background_depan'] ?>'); 
	background-position-y: -1px;
}
.kartu-belakang {
	margin-top: <?= $margin_kartu_depan_belakang ?>px;
	background-image:url('<?= $config['base_url'] . $config['kartu_path'] . $setting_kartu['background_belakang'] ?>'); 
}

.kartu-tandatangan {
	position: absolute;
	top: <?= $setting_kartu['ttd_text_margin_top'] * $printer['dpi'] / $inchi ?>px;
	left: <?= $setting_kartu['ttd_text_margin_left'] * $printer['dpi'] / $inchi ?>px;
	text-align: <?= $setting_kartu['ttd_text_align'] ?>;
	font-size: <?= $setting_kartu['ttd_text_font_size'] ?>px;
	font-family: <?= $setting_kartu['ttd_text_font_family'] ?>;
	font-weight: <?= $setting_kartu['ttd_text_font_weight'] ?>;
}
.kartu-tandatangan p{
	margin:0;
	padding:0;
	line-height: normal;
	white-space: nowrap;
}

.kartu-tandatangan-sign {
	position: absolute;
	width: <?= $setting_kartu['ttd_sign_width'] * $printer['dpi'] / $inchi ?>px;
	height: <?= $setting_kartu['ttd_sign_height'] * $printer['dpi'] / $inchi ?>px;
	top: <?= $setting_kartu['ttd_sign_margin_top'] * $printer['dpi'] / $inchi ?>px;
	left: <?= $setting_kartu['ttd_sign_margin_left'] * $printer['dpi'] / $inchi ?>px;
}
.kartu-tandatangan-sign img{
	max-width: <?= $setting_kartu['ttd_sign_width'] * $printer['dpi'] / 25.4; ?>px;
	max-height: <?= $foto_container_height = $setting_kartu['ttd_sign_height'] * $printer['dpi'] / 25.4; ?>px;
}

.kartu-tandatangan-cap {
	position: absolute;
	width: <?= $setting_kartu['ttd_cap_width'] * $printer['dpi'] / $inchi ?>px;
	height: <?= $setting_kartu['ttd_cap_height'] * $printer['dpi'] / $inchi ?>px;
	top: <?= $setting_kartu['ttd_cap_margin_top'] * $printer['dpi'] / $inchi ?>px;
	left: <?= $setting_kartu['ttd_cap_margin_left'] * $printer['dpi'] / $inchi ?>px;
	z-index: 5;
}
.kartu-tandatangan-cap img{
	max-width: <?= $setting_kartu['ttd_cap_width'] * $printer['dpi'] / 25.4; ?>px;
	max-height: <?= $foto_container_height = $setting_kartu['ttd_cap_height'] * $printer['dpi'] / 25.4; ?>px;
}
.berlaku-container {
	position: absolute;
    top: <?= $setting_kartu['berlaku_margin_top'] * $printer['dpi'] / 25.4; ?>px;
    left: <?= $setting_kartu['berlaku_margin_left'] * $printer['dpi'] / 25.4; ?>px;
	font-family: <?= $setting_kartu['berlaku_font_family'] ?>;
	font-size: <?= $setting_kartu['berlaku_font_size'] ?>px;
	font-weight: <?= $setting_kartu['berlaku_font_weight'] ?>;
}
</style>
</head>
<body>

<div class=" cetak-container">
	<?php
	function showTtd($setting_kartu, $printer, $show = true)
	{

		global $config;

		$ttd_text = str_replace('{{YEAR}}', date('Y'), $setting_kartu['ttd_text']);
		$exp = explode("\r\n", $ttd_text);

		$content = '';
		$space = false;
		foreach ($exp as $val) {
			$val = trim($val);
			if ($val) {
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
		</div>
		<div class="kartu-tandatangan-cap">
			<img id="ttd-cap-image" src="' . $config['base_url'] . 'public/images/kartu/' . $setting_kartu['ttd_cap_image'] . '"/>
		</div>
	</div>';

		return $html;
	}

	function berlakuKartu($setting_kartu, $show = true)
	{
		$display = $show ? '' : ' style="display:none"';
		$html = '<div class="berlaku-container"' . $display . '>';
		if ($setting_kartu['berlaku_jenis'] == 'periode') {
			$exp = explode('-', $setting_kartu['berlaku_hingga_tanggal']);
			$html .= $setting_kartu['berlaku_periode_prefix'] . ' ' . $exp[2] . ' ' . nama_bulan((int) $exp[1]) . ' ' . $exp[0];
		} else {
			$html .= $setting_kartu['berlaku_custom_text'];
		}
		$html .= '</div>';
		return $html;
	}

	function showQrcode($setting_kartu, $printer, $siswa, $show = true)
	{
		$display = $show ? '' : 'display:none;';
		$qrcode_margin_left = $setting_kartu['qrcode_margin_left'] * $printer['dpi'] / 25.4;
		$qrcode_margin_top = $setting_kartu['qrcode_margin_top'] * $printer['dpi'] / 25.4;
		$content = '<div class="qrcode-container" style="position:absolute;' . $display . 'z-index:6;top:' . $qrcode_margin_top . 'px;left:' . $qrcode_margin_left . 'px;padding:' . $setting_kartu['qrcode_padding'] . ';background:#FFFFFF">{{CONTENT}}</div>';

		if ($siswa['qrcode_text']) {
			$qrcode_text = $siswa['qrcode_text'];
		} else {
			if ($setting_kartu['qrcode_content_jenis'] == 'field_database') {
				$qrcode_text = $siswa[$setting_kartu['qrcode_content_field_database']];
			} else {
				$qrcode_text = $setting_kartu['qrcode_content_global_text'];
			}
		}

		$qrcode_content = generateQRCode($setting_kartu['qrcode_version'], $setting_kartu['qrcode_ecc_level'], $qrcode_text, $setting_kartu['qrcode_size_module']);
		$content = str_replace('{{CONTENT}}', $qrcode_content, $content);

		return $content;
	}

	foreach ($id as $val) { ?>
		<div class="kartu-container">
			<div class="kartu-content-container kartu-depan">
				<div class="kartu-foto">
					<?php
					if ($nama[$val]['foto'] && file_exists($config['foto_path'] . $nama[$val]['foto'])) {
					?>
						<img src="<?= $config['base_url'] . $config['foto_path'] . $nama[$val]['foto'] ?>" />
					<?php } ?>
				</div>
				<div class="kartu-detail">
					<table cellspacing="0" cellpadding="0">
						<?php

						foreach ($siswa_data_digunakan as $index => $val_digunakan) {

							$data_siswa = '';
							foreach ($fields as $name => $value) {

								if (strpos($val_digunakan['pattern'], $name) !== false) {
									$data_value = $nama[$val][$name];
									if (strpos($name, 'tgl') !== false) {
										$exp = explode('-', $data_value);
										$data_value = $exp[2] . ' ' . nama_bulan($exp[1] * 1) . ' ' . $exp[0];
									}

									$val_digunakan['pattern'] = str_replace($name, $data_value, $val_digunakan['pattern']);
								}
							}

							if ($setting_kartu['data_depan_show_label'] == 'Y') {
								echo '<tr class="text-' . $val_digunakan['pattern'] . '">
									<td class="label">' . $val_digunakan['judul_data'] . '</td>
									<td class="separator">:</td>
									<td>' . $val_digunakan['pattern'] . '</td>
								</tr>';
							} else {
								echo '<tr class="text-' . $index . '">
								<td>' . $val_digunakan['pattern'] . '</td>
							</tr>';
							}
						}
						?>
					</table>
				</div>

				<?php
				if ($setting_kartu['ttd_gunakan'] == 'Y' && $setting_kartu['ttd_posisi'] == 'depan') {
					echo showTtd($setting_kartu, $printer);
				}

				if ($setting_kartu['berlaku_gunakan'] == 'Y' && $setting_kartu['berlaku_posisi'] == 'depan') {
					echo berlakuKartu($setting_kartu);
				}

				if ($setting_kartu['qrcode_gunakan'] == 'Y' && $setting_kartu['qrcode_posisi'] == 'depan') {
					echo showQrcode($setting_kartu, $printer, $siswa);
				}
				?>
			</div>

			<div class="kartu-content-container kartu-belakang">
				<?php

				if ($setting_kartu['ttd_gunakan'] == 'Y' && $setting_kartu['ttd_posisi'] == 'belakang') {
					echo showTtd($setting_kartu, $printer);
				}

				if ($setting_kartu['berlaku_gunakan'] == 'Y' && $setting_kartu['berlaku_posisi'] == 'belakang') {
					echo berlakuKartu($setting_kartu);
				}

				if ($setting_kartu['qrcode_gunakan'] == 'Y' && $setting_kartu['qrcode_posisi'] == 'belakang') {
					echo showQrcode($setting_kartu, $printer, $nama[$val]);
				}
				?>

			</div>
		</div>
	<?php
	}
	?>
	<div class="clearfix"></div>
	</div>
	</body>

</html>