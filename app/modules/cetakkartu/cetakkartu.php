<?php

/**
 *	Aplikasi Cetak Kartu
 *	Developed by: Agus Prawoto Hadi
 *	Website		: www.pdsionline.org
 *	Year		: 2021
 */

$js[] = BASE_URL . 'public/vendors/datatables/dist/js/jquery.dataTables.min.js';
$js[] = BASE_URL . 'public/vendors/datatables/dist/js/dataTables.bootstrap5.min.js';
$styles[] = BASE_URL . 'public/vendors/datatables/dist/css/dataTables.bootstrap5.min.css';
// $js[] = BASE_URL . 'public/themes/modern/js/data-tables-ajax.js';
$js[] = BASE_URL . 'public/themes/modern/js/cetakkartu.js';

$site_title = 'Cetak Kartu';
$inchi = 25.4;
function generateTtdKartu($setting_kartu, $printer)
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

	$html = '
	<style>
		
		.kartu-tandatangan {
			margin-top: ' . ($printer['margin_top'] + $setting_kartu['ttd_text_margin_top']) . 'mm;
			margin-left: ' . $setting_kartu['ttd_text_margin_left'] . 'mm;
			text-align: ' . $setting_kartu['ttd_text_align'] . ';
			font-size: ' . $setting_kartu['ttd_text_font_size'] . 'px;
			font-family: ' . $setting_kartu['ttd_text_font_family'] . ';
			font-weight: normal;
		}
		.kartu-tandatangan p{
			margin:0;
			padding:0;
			line-height: normal;
			white-space: nowrap;
		}
				
	</style>
		
	<div class="kartu-tandatangan">' . $content . '</div>';
	return $html;
}


function showTtdSign($setting_kartu, $printer)
{

	global $config;

	$html = '
	<style>
		
		.kartu-tandatangan-sign {
			width: ' . $setting_kartu['ttd_sign_width'] . 'mm;
			height: ' . $setting_kartu['ttd_sign_height'] . 'mm;
			margin-top: ' . ($printer['margin_top'] + $setting_kartu['ttd_sign_margin_top']) . 'mm;
			margin-left: ' . $setting_kartu['ttd_sign_margin_left'] . 'mm;
		}
		
		.kartu-tandatangan-sign img {
			max-width: ' . $setting_kartu['ttd_sign_width'] . 'mm;
			max-height: ' . $setting_kartu['ttd_sign_height'] . 'mm
		}
				
	</style>

	<div class="kartu-tandatangan-sign">
		<img id="ttd-sign-image" src="' . $config['base_url'] . 'public/images/kartu/' . $setting_kartu['ttd_sign_image'] . '"/>
	</div>';

	return $html;
}

function showTtdCap($setting_kartu, $printer)
{

	global $config;

	$html = '
	<style>
		.kartu-tandatangan-cap {
		
			width: ' . $setting_kartu['ttd_cap_width'] . 'mm;
			height: ' . $setting_kartu['ttd_cap_height'] . 'mm;
			margin-top: ' . ($printer['margin_top'] + $setting_kartu['ttd_cap_margin_top']) . 'mm;
			margin-left: ' . $setting_kartu['ttd_cap_margin_left'] . 'mm;
			z-index: 5;
		}
		.kartu-tandatangan-cap img{
			max-width: ' . $setting_kartu['ttd_cap_width'] . 'mm;
			max-height: ' . $setting_kartu['ttd_cap_height'] . 'mm
		}
				
	</style>
	<div class="kartu-tandatangan-cap">
		<img id="ttd-cap-image" src="' . $config['base_url'] . 'public/images/kartu/' . $setting_kartu['ttd_cap_image'] . '"/>
	</div>';

	return $html;
}

function masaBerlakuKartu($setting_kartu, $printer)
{
	$html = '
	<style>
	.berlaku-container {
		margin-top: ' . ($printer['margin_top'] + $setting_kartu['berlaku_margin_top']) . 'mm;
		margin-left: ' . $setting_kartu['berlaku_margin_left'] . 'mm;
		font-family: ' . $setting_kartu['berlaku_font_family'] . ';
		font-size: ' . $setting_kartu['berlaku_font_size'] . 'px;
		font-weight: normal;
	}
	</style>
	<div class="berlaku-container">';

	if ($setting_kartu['berlaku_jenis'] == 'periode') {
		$exp = explode('-', $setting_kartu['berlaku_hingga_tanggal']);
		$html .= $setting_kartu['berlaku_periode_prefix'] . ' ' . $exp[2] . ' ' . nama_bulan((int) $exp[1]) . ' ' . $exp[0];
	} else {
		$html .= $setting_kartu['berlaku_custom_text'];
	}
	$html .= '</div>';
	return $html;
}

function showQrcodeKartu($setting_kartu, $printer, $nama)
{
	if ($nama['qrcode_text']) {
		$qrcode_text = $nama['qrcode_text'];
	} else {
		if ($setting_kartu['qrcode_content_jenis'] == 'field_database') {
			$qrcode_text = $nama[$setting_kartu['qrcode_content_field_database']];
		} else {
			$qrcode_text = $setting_kartu['qrcode_content_global_text'];
		}
	}

	$qrcode_image = generateQRCode($setting_kartu['qrcode_version'], $setting_kartu['qrcode_ecc_level'], $qrcode_text, $setting_kartu['qrcode_size_module'], 'image');
	return $qrcode_image;
}

$jarak_kartu_kiri_kanan = 12; //mm
switch ($_GET['action']) {
	default:
		action_notfound();

		// INDEX 
	case 'index':

		$sql = 'SELECT * FROM siswa';
		$data['result'] = $db->query($sql)->getResultArray();


		$sql_propinsi = 'SELECT * FROM wilayah_propinsi';
		$provinceData = $db->query($sql_propinsi)->getResultArray();

		foreach ($data['result'] as &$data) {
			$provinceId = $data['province'];
			$provinceName = '';

			// Cari nama provinsi berdasarkan ID provinsi
			foreach ($provinceData as $province) {
				if ($province['id_wilayah_propinsi'] == $provinceId) {
					$provinceName = $province['nama_propinsi'];
					break;
				}
			}

			// Masukkan nama provinsi ke dalam data siswa
			$data['result']['province'] = $provinceName;
		}


		if (!$data['result']) {
			$data['msg']['status'] = 'error';
			$data['msg']['content'] = 'Data tidak ditemukan';
		}

		load_view('views/result.php', $data);

	case 'pdf':

		$data = set_data();

		$sql = 'SELECT * FROM siswa WHERE id_siswa = ?';
		$result = $db->query($sql, $_GET['id'])->getRowArray();

		$sql_propinsi = 'SELECT * FROM wilayah_propinsi';
		$provinceData = $db->query($sql_propinsi)->getResultArray();

		foreach ($result as &$data) {
			$provinceId = $data['province'];
			$provinceName = '';

			// Cari nama provinsi berdasarkan ID provinsi
			foreach ($provinceData as $province) {
				if ($province['id_wilayah_propinsi'] == $provinceId) {
					$provinceName = $province['nama_propinsi'];
					break;
				}
			}

			// Masukkan nama provinsi ke dalam data siswa
			$data['province'] = $provinceName;
		}
		$data['nama'] = $result;

		$sql = 'SELECT * FROM siswa_data_digunakan WHERE id_setting_kartu = ? ORDER BY urut';
		$result = $db->query($sql, $data['setting_kartu']['id_setting_kartu'])->getResultArray();
		$data['siswa_data_digunakan'] = $result;

		$data['fields'] = $db->getField('siswa');

		extract($data);
		foreach ($fields as $val) {
			$tmp_fields[$val['column_name']] = strlen($val['column_name']);
		}
		arsort($tmp_fields);
		$fields = $tmp_fields;

		require_once BASEPATH . 'app/libraries/vendors/mpdf/autoload.php';

		$mpdf = new \Mpdf\Mpdf();
		$mpdf->debug = true;
		$mpdf->showImageErrors = true;

		// set document information
		$mpdf->SetCreator('Jagowebdev');
		$mpdf->SetAuthor('Agus Prawoto Hadi');
		$mpdf->SetTitle('Kartu Elektronik');
		$mpdf->SetSubject('Kartu Pelajar');

		$mpdf->AddPageByArray([
			'margin-left' => $printer['margin_left'],
			'margin-right' => 0,
			'margin-top' => $printer['margin_top'],
			'margin-bottom' => 0,
		]);

		$kartu_width = $setting_kartu['kartu_width'] * $printer['dpi'] / 25.4;
		$kartu_height = $setting_kartu['kartu_height'] * $printer['dpi'] / 25.4;

		$html = '
		
		<div class="kartu-content-container kartu-depan" style="width: ' . $kartu_width . 'px;height: ' . $kartu_height . 'px;background-repeat: no-repeat; background-size: 100% auto; background:url(' . $config['base_url'] . $config['kartu_path'] . $setting_kartu['background_depan'] . ')"></div>';

		$mpdf->WriteHTML($html);
		$mpdf->SetXY(0, 0);

		$html = '
		<style>
			.kartu-belakang {
				width: ' . ($kartu_width + 5) . 'px;
				height: ' . ($kartu_height + 5) . 'px;
				margin-top: ' . $printer['margin_top'] . 'mm;
				margin-left:' .  ($jarak_kartu_kiri_kanan  + $setting_kartu['kartu_width']) . 'mm;
				background-repeat: no-repeat;
				background-size: 100% auto;
				background:url("' . $config['base_url'] . $config['kartu_path'] . $setting_kartu['background_belakang'] . '")
			}
		</style>
		<div class="kartu-content-container kartu-belakang"></div>';

		$mpdf->WriteHTML($html);

		$html = '
			<style>
			body{font-family:arial; color: #2a2a2a;}
			.kartu-detail {
				margin-left:' . $setting_kartu['data_depan_margin_left'] . 'mm;
				margin-top:' . ($printer['margin_top'] + $setting_kartu['data_depan_margin_top']) . 'mm 
			}
			.kartu-detail td {
				font-family:' . $setting_kartu['data_depan_font_family'] . ';
				font-size:' . $setting_kartu['data_depan_font_size'] . 'px;
				line-height:10px;
			}
			.kartu-detail .label {
				width:' . $setting_kartu['data_depan_label_width'] . 'mm;
			}
			</style>
			<div class="kartu-detail">	
			<table>';
		foreach ($siswa_data_digunakan as $val_digunakan) {

			foreach ($fields as $name => $value) {

				if (strpos($val_digunakan['pattern'], $name) !== false) {
					$data_value = $nama[$name];
					if ($name == 'jenis_kelamin') {
						$data_value = $data_value == 'L' ? 'Laki-laki' : 'Perempuan';
					}
					if (strpos($name, 'tgl') !== false) {
						$exp = explode('-', $data_value);
						$data_value = $exp[2] . ' ' . nama_bulan($exp[1] * 1) . ' ' . $exp[0];
					}

					$val_digunakan['pattern'] = str_replace($name, $data_value, $val_digunakan['pattern']);
				}
			}

			if ($setting_kartu['data_depan_show_label'] == 'Y') {
				$html .= '<tr>
								<td class="label">' . $val_digunakan['judul_data'] . '</td>
								<td>:</td>
								<td>' . $val_digunakan['pattern'] . '</td>
							</tr>';
			} else {
				$html .= '<tr>
								<td>' . $val_digunakan['pattern'] . '</td>
							</tr>';
			}
		}

		$html .= '
			</table>
		</div>';
		$mpdf->SetXY(0, 0);
		$mpdf->WriteHTML($html);

		if ($nama['foto'] && file_exists($config['foto_path'] . $nama['foto'])) {
			$html = '
					<style>
					.kartu-foto {
						margin-left: ' . $setting_kartu['foto_margin_left'] . 'mm;
						margin-top: ' . ($printer['margin_top'] + $setting_kartu['foto_margin_top']) . 'mm;
					}
					
					</style>
		
					<div class="kartu-foto">
						<img style="max-width:' . $setting_kartu['foto_width'] . 'mm;max-height:' . $setting_kartu['foto_height'] . 'mm" src="' . $config['foto_path'] . $nama['foto'] . '"/>
					</div>';
		}

		$mpdf->SetXY(0, 0);
		$mpdf->WriteHTML($html);

		if ($setting_kartu['ttd_gunakan'] == 'Y' && $setting_kartu['ttd_posisi'] == 'depan') {
			$html = generateTtdKartu($setting_kartu, $printer);
			$mpdf->SetXY(0, 0);
			$mpdf->WriteHTML($html);

			$html = showTtdSign($setting_kartu, $printer);
			$mpdf->SetXY(0, 0);
			$mpdf->WriteHTML($html);

			$html = showTtdCap($setting_kartu, $printer);
			$mpdf->SetXY(0, 0);
			$mpdf->WriteHTML($html);
		}

		if ($setting_kartu['berlaku_gunakan'] == 'Y' && $setting_kartu['berlaku_posisi'] == 'depan') {
			$html = masaBerlakuKartu($setting_kartu, $printer);
			$mpdf->SetXY(0, 0);
			$mpdf->WriteHTML($html);
		}

		if ($setting_kartu['qrcode_gunakan'] == 'Y' && $setting_kartu['qrcode_posisi'] == 'depan') {
			$html = showQrcodeKartu($setting_kartu, $printer, $nama);
			$mpdf->SetXY(0, 0);
			$mpdf->WriteHTML($html);
		}

		// Belakang
		if ($setting_kartu['qrcode_gunakan'] == 'Y' && $setting_kartu['qrcode_posisi'] == 'belakang') {
			$qrcode_image = showQrcodeKartu($setting_kartu, $printer, $nama);
			$mpdf->SetXY(0, 0);

			$dim = getimagesize($qrcode_image);
			$pxtomm = 0.2645833333;
			$qrcode_width = $dim[0] * $pxtomm;

			$add_left = 0;
			$add_top = 0;
			if ($qrcode_width > 15) {
				$add_left = $qrcode_width - 15;
				$add_top = $qrcode_width - 15 - 2;
				$qrcode_width = 15;
			}
			// echo $dim[0] * $pxtomm; die;
			$mpdf->Image($qrcode_image, ($printer['margin_left'] + $setting_kartu['kartu_width'] + $jarak_kartu_kiri_kanan + $setting_kartu['qrcode_margin_left'] + $add_left), $printer['margin_top'] + $setting_kartu['qrcode_margin_top'] + $add_top,  $qrcode_width, 0, 'png');


			unlink($qrcode_image);
		}

		if (!empty($_POST['email'])) {
			$filename = 'public/tmp/kartu_' . time() . '.pdf';
			$mpdf->Output($filename, 'F');

			require_once 'app/config/email.php';
			$email_config = new EmailConfig;
			$email_data = array(
				'from_email' => $email_config->from, 'from_title' => 'Aplikasi Kartu Elektronik', 'to_email' => $_POST['email'], 'to_name' => $nama['nama'], 'email_subject' => 'Permintaan Kartu Elektronik', 'email_content' => '<h1>KARTU ELEKTRONIK</h1><h2>Hi. ' . $nama['nama'] . '</h2><p>Berikut kami sertakan kartu elektronik atas nama Anda. Anda dapat mengunduhnya pada bagian Attachment.<br/><br/><p>Salam</p>', 'attachment' => ['path' => $filename, 'name' => 'Kartu Elektronik.pdf']
			);

			require_once('app/libraries/PhpmailerLib.php');

			$phpmailer = new \App\Libraries\PhpmailerLib;
			$phpmailer->init();
			$phpmailer->setProvider($email_config->provider);
			$send_email =  $phpmailer->send($email_data);

			unlink($filename);
			if ($send_email['status'] == 'ok') {
				$message['status'] = 'ok';
				$message['message'] = 'Kartu elektronik berhasil dikirim ke alamat email: ' . $_POST['email'];
			} else {
				$message['status'] = 'error';
				$message['message'] = 'Kartu elektronik gagal dikirim ke alamat email: ' . $_POST['email'] . '<br/>Error: ' . $send_email['message'];
			}
			echo json_encode($message);
			exit();
		}

		$mpdf->Output('Kartu Elektronik.pdf', 'D');
		exit();

	case 'print':
		$where_own = where_own();

		$data = set_data();

		$sql = 'SELECT * FROM siswa_data_digunakan WHERE id_setting_kartu = "' . $data['setting_kartu']['id_setting_kartu'] . '" ORDER BY urut';
		$result = $db->query($sql)->getResultArray();

		$data['siswa_data_digunakan']	= $result;

		$data['id'] = $_GET['id'];
		$allowed = true;
		foreach ($data['id'] as $key => $val) {
			$sql = 'SELECT * FROM siswa ' . where_own() . ' AND id_siswa = ' . $val;
			$result = $db->query($sql)->result();

			$sql_propinsi = 'SELECT * FROM wilayah_propinsi';
			$provinceData = $db->query($sql_propinsi)->getResultArray();

			$provinceId = $result[0]['province'];
			$provinceName = '';

			// Cari nama provinsi berdasarkan ID provinsi
			foreach ($provinceData as $province) {
				if ($province['id_wilayah_propinsi'] == $provinceId) {
					$provinceName = $province['nama_propinsi'];
					break;
				}
			}

			// Masukkan nama provinsi ke dalam data siswa
			$result[0]['province'] = $provinceName;

			if (!$result) {
				$allowed = false;
				break;
			}
		}

		if (!$allowed) {
			exit_error('Anda tidak diperbolehkan mengakses data ini');
		}

		foreach ($data['id'] as $key => $val) {
			$sql = 'SELECT * FROM siswa WHERE id_siswa = ?';
			$result = $db->query($sql, trim($val))->result();

			$sql_propinsi = 'SELECT * FROM wilayah_propinsi';
			$provinceData = $db->query($sql_propinsi)->getResultArray();

			$provinceId = $result[0]['province'];
			$provinceName = '';

			// Cari nama provinsi berdasarkan ID provinsi
			foreach ($provinceData as $province) {
				if ($province['id_wilayah_propinsi'] == $provinceId) {
					$provinceName = $province['nama_propinsi'];
					break;
				}
			}

			// Masukkan nama provinsi ke dalam data siswa
			$result[0]['province'] = $provinceName;

			$data['nama'][$val]	= $result[0];
		}

		$view = load_view('views/cetak.php', $data, true);
		echo $view;
		die;

	case 'getDataDT':

		$result['draw'] = $start = $_POST['draw'] ?: 1;

		$data_table = getListData();
		$result['recordsTotal'] = $data_table['total_data'];
		$result['recordsFiltered'] = $data_table['total_filtered'];

		helper('html');
		$id_user = $_SESSION['user']['id_user'];

		foreach ($data_table['content'] as $key => &$val) {
			$foto = 'Anonymous.png';
			if ($val['foto']) {
				if (file_exists('public/images/foto/' . $val['foto'])) {
					$foto = $val['foto'];
				} else {
					$foto = 'noimage.png';
				}
			}

			$val['foto'] = '<div class="list-foto"><img src="' . BASE_URL . 'public/images/foto/' . $foto . '"/></div>';

			$val['tgl_lahir'] = $val['tempat_lahir'] . ', ' . format_tanggal($val['tgl_lahir']);
			$val['ignore_search_checkall'] = '<div class="form-check"><input type="checkbox" class="form-check-input checkbox" name="id[]" value="' . $val['id_siswa'] . '">';

			$val['ignore_search_action'] = '-';
			if ($list_action['read_data'] !== 'no') {
				$val['ignore_search_action'] =
					'<div class="btn-group">
									<a class="btn d-flex btn-xs align-items-center btn-danger" href="' . $config['base_url'] . 'cetakkartu/pdf?id[]=' . $val['id_siswa'] . '"><i class="fas fa-file-pdf me-1"></i>PDF</a>
									<a class="btn d-flex btn-xs align-items-center btn-success" href="' . $config['base_url'] . 'cetakkartu/print?id[]=' . $val['id_siswa'] . '"><i class="fas fa-print me-1"></i>Cetak</a>
									<button type="button" class="btn d-flex btn-xs align-items-center btn-primary kirim-email" data-id="' . $val['id_siswa'] . '" data-email="' . $val['email'] . '" ><i class="fas fa-paper-plane me-1"></i>Email</button>
								</div>';
			}
		}

		$result['data'] = $data_table['content'];
		echo json_encode($result);
		exit();
}

function getListData()
{

	global $db;
	$columns = $_POST['columns'];
	$order_by = '';

	// Search
	$search_all = @$_POST['search']['value'];
	$where = where_own();
	if ($search_all) {
		// Additional Search
		$columns[]['data'] = 'tempat_lahir';

		foreach ($columns as $val) {

			if (strpos($val['data'], 'ignore_search') !== false)
				continue;

			if (strpos($val['data'], 'ignore') !== false)
				continue;

			$where_col[] = $val['data'] . ' LIKE "%' . $search_all . '%"';
		}
		$where .= ' AND (' . join(' OR ', $where_col) . ') ';
	}

	// Order
	$order = $_POST['order'];

	if (@$order[0]['column'] != '') {
		$order_by = ' ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']);
	}

	$start = $_POST['start'] ?: 0;
	$length = $_POST['length'] ?: 10;

	// Query Total
	$sql = 'SELECT COUNT(*) AS jml_data FROM siswa' . where_own();
	$query = $db->query($sql)->getRowArray();
	$total_data = $query['jml_data'];

	// Query Filtered
	$sql = 'SELECT COUNT(*) AS jml_data FROM siswa' . $where;
	$query = $db->query($sql)->getRowArray();
	$total_filtered = $query['jml_data'];

	// Query Data
	$sql = 'SELECT * FROM siswa 
			' . $where . $order_by . ' LIMIT ' . $start . ', ' . $length;
	$content = $db->query($sql)->getResultArray();

	$sql_propinsi = 'SELECT * FROM wilayah_propinsi';
	$provinceData = $db->query($sql_propinsi)->getResultArray();

	foreach ($content as &$data) {
		$provinceId = $data['province'];
		$provinceName = '';

		// Cari nama provinsi berdasarkan ID provinsi
		foreach ($provinceData as $province) {
			if ($province['id_wilayah_propinsi'] == $provinceId) {
				$provinceName = $province['nama_propinsi'];
				break;
			}
		}

		// Masukkan nama provinsi ke dalam data siswa
		$data['province'] = $provinceName;
	}

	return ['total_data' => $total_data, 'total_filtered' => $total_filtered, 'content' => $content];
}

function set_data()
{
	global $setting_web, $current_module, $db;

	$data['setting_web'] = $setting_web;
	$data['app_module'] = $current_module;

	$sql = 'SELECT * FROM setting_kartu WHERE gunakan = "Y"';
	$result = $db->query($sql)->getRowArray();
	$data['setting_kartu']	= $result;

	/* $sql = 'SELECT * FROM setting_qrcode';
	$result = $db->query($sql)->row();
	$data['qrcode']	= $result; */

	$sql = 'SELECT * FROM setting_printer WHERE gunakan = 1';
	$result = $db->query($sql)->row();
	$data['printer']	= $result;

	$data['fields'] = $db->getField('siswa');

	return $data;
}
