<?php
/**
*	Aplikasi Cetak Kartu
*	Developed by: Agus Prawoto Hadi
*	Website		: www.pdsionline.org
*	Year		: 2021
*/

// $js[] = BASE_URL . 'public/vendors/displace/displace.js';
$js[] = BASE_URL . 'public/themes/modern/js/setting-qrcode.js';

$fields = ['version', 'ecc', 'size_module', 'padding', 'content_global_text'];
switch ($_GET['action']) 
{
	default: 
		action_notfound();
		
	// INDEX 
	case 'preview-qrcode':
		
		if ($_GET['content_jenis'] == 'field_database') {
			$field_database = $_GET['content_field_database'] ;
			$sql = 'SELECT ' . $field_database . ' FROM siswa LIMIT 1';
			$result = $db->query($sql)->getRowArray();
			$content = $result[$field_database];
		} else {
			$content = $_GET['content_global_text'];
		}
		
		if (!trim($content)) {
			echo '<div class="alert alert-warning">Data QR Code masih kosong</div>';
			exit;
		}
		
		require BASE_PATH . 'app/libraries' . DS . 'vendors' . DS . 'qrcode' . DS . 'qrcode_extended.php';
		if (is_int($_GET['size_module'])) {
			$height = $_GET['size_module'] % 2 ? $_GET['size_module'] : $_GET['size_module'] + 0.5;
		} else {
			$height = $_GET['size_module'];
		}

		$qr = new QRCodeExtended();
		
		$ecc = ['L' => QR_ERROR_CORRECT_LEVEL_L
				, 'M' => QR_ERROR_CORRECT_LEVEL_M
				, 'Q' => QR_ERROR_CORRECT_LEVEL_Q
				, 'H' => QR_ERROR_CORRECT_LEVEL_H
			];
		$qr->setErrorCorrectLevel($ecc[$_GET['ecc']]);
		$qr->setTypeNumber($_GET['version']);
		$qr->addData($content);
		$cek = $qr->checkError();
		if ($cek['status'] == 'success') {
			$qr->make();
			echo $qr->saveHtml($_GET['size_module']);
		} else {
			echo '<div class="alert alert-warning">' . $cek['content'] . '</div>';
		}
		die;

	case 'index':
		
		if (empty($_GET['id'])) {
			$_GET['id'] = '0';
		}
		
		$sql = 'SELECT * FROM setting_kartu WHERE id_setting_kartu = ' . $_GET['id'];
		$result = $db->query($sql)->row();
		$data['title'] = 'Edit ' . $current_module['judul_module'];

		if (!$result) {
			$data['message']['status'] = 'error';
			$data['message']['content'] = 'Data setting kartu tidak ditemukan';
			load_view('views/view_error.php', $data);
			break;
		}
		
		if (!empty($_POST['submit'])) 
		{
			if ($module_role[$_SESSION['user']['id_user']]['update_data'] == 'all')
			{
				$form_errors = validate_form();
				if ($form_errors) {
					$data['message']['status'] = 'error';
					$data['message']['content'] = $form_errors;
				} else {
					// Logo Login
						foreach ($fields as $field) {
							$data_db[$field] = $_POST[$field];
						}
						
						$data_db_setting['qrcode_margin_top'] = $_POST['qrcode_margin_top'];
						$data_db_setting['qrcode_margin_left'] = $_POST['qrcode_margin_left'];
						$data_db_setting['qrcode_posisi'] = $_POST['qrcode_posisi'];

						$query = $db->update('setting_qrcode', $data_db);
						$query = $db->update('setting_kartu', $data_db_setting);
						
						if ($query) 
						{
							$data['message']['status'] = 'ok';
							$data['message']['content'] = 'Data berhasil disimpan';
							
						} else {
							$data['message']['status'] = 'error';
							$data['message']['content'] = 'Data gagal disimpan';
						}
					}
			} else {
				$data['message']['status'] = 'error';
				$data['message']['content'] = 'Role anda tidak diperbolehkan melakukan perubahan';
			}	
		}
		
		$sql = 'SELECT * FROM setting_qrcode WHERE id_setting_kartu = ?';
		$query = $db->query($sql, $_GET['id'])->row();
		
		if ($query) {
			$exists= true;
			foreach($query as $key => $val) {
				$data[$key] = $val;
			}
		}
		
		$sql = 'SELECT * FROM setting_kartu WHERE id_setting_kartu = ' . $_GET['id'];
		$result = $db->query($sql)->row();
		
		$data['layout']	= $result;
		$data['background'] = [
				'background_depan' => $result['background_depan']
				,'background_belakang'  => $result['background_belakang']
			];
		
		$sql = 'SELECT * FROM setting_printer';
		$result = $db->query($sql)->result();
		$data['printer']	= $result[0];
						
		$fields = $db->getField('siswa');
		foreach ($fields as $val) {			
			$data['field_table'][$val['column_name']] = $val['column_name'];
		}
		
		load_view('views/form.php', $data);
}

function validate_form() 
{
	require_once('app/libraries/FormValidation.php');
	$validation = new FormValidation();
	$validation->setRules('version', 'Version', 'trim|required');
	$validation->setRules('ecc', 'ECC', 'trim|required');
	$validation->setRules('size_module', 'Size Module', 'trim|required');
	$validation->setRules('padding', 'Padding Tepi', 'trim|required');
	$validation->setRules('qrcode_posisi', 'Posisi Kartu', 'trim|required');
	
	$validation->validate();
	$form_errors =  $validation->getMessage();
	
	return $form_errors;
}