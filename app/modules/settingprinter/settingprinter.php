<?php
/**
*	Aplikasi Cetak Kartu
*	Developed by: Agus Prawoto Hadi
*	Website		: www.pdsionline.org
*	Year		: 2021
*/

$js[] = BASE_URL . 'public/themes/modern/js/set-default.js';
$js[] = BASE_URL . 'public/themes/modern/js/setting-printer.js';
$site_title = 'Home Page';

switch ($_GET['action']) 
{
	default: 
		action_notfound();
		
	// INDEX 
	case 'index':
		
		if (!empty($_POST['delete'])) 
		{
			$result = $db->delete('setting_printer', ['id_setting_printer' => $_POST['id']]);
			// $result = true;
			if ($result) {
				$sql = 'SELECT COUNT(*) AS jml_data FROM setting_printer WHERE gunakan = 1';
				$jml_data = $db->query($sql)->row();
				if ($jml_data['jml_data'] == 0) {
					$sql = 'SELECT id_setting_printer FROM setting_printer ORDER BY id_setting_printer DESC LIMIT 1';
					$id = $db->query($sql)->row();
					if ($id) {
						$data_db['gunakan'] = 1;
						$query = $db->update('setting_printer', $data_db, 'id_setting_printer = ' . $id['id_setting_printer']);
					}
				}
				$data['msg'] = ['status' => 'ok', 'message' => 'Data berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'error', 'message' => 'Data gagal dihapus'];
			}
		}
		$sql = 'SELECT * FROM setting_printer ORDER BY gunakan DESC';
		$data['result'] = $db->query($sql)->result();
		
		if (!$data['result']) {
			$data['msg']['status'] = 'error';
			$data['msg']['message'] = 'Data tidak ditemukan';
		}
		
		load_view('views/result.php', $data);
	
	case 'add': 
		$breadcrumb['Add'] = '';
		$data['msg'] = [];
		$data['title'] = 'Tambah ' . $current_module['judul_module'];
		
		if (isset($_POST['submit'])) 
		{
			$data['message'] = save_data();
		}
		load_view('views/form.php', $data);
		
	case 'edit': 
		
		$data['message'] = [];
		if (isset($_POST['submit'])) 
		{
			$data['message'] = save_data();
		}
		
		$breadcrumb['Edit'] = '';
		
		$sql = 'SELECT * FROM setting_printer WHERE id_setting_printer = ?';
		$result = $db->query($sql, trim($_GET['id']))->result();
		$data	= $result[0];
		
		$data['title'] = 'Edit ' . $current_module['judul_module'];
		load_view('views/form.php', $data);
		
	case 'set-default':
		if (isset($_POST['submit'])) 
		{
			$data_db['gunakan'] = 0;
			$query = $db->update('setting_printer', $data_db);
			$query = $db->update('setting_printer', ['gunakan' => 1], 'id_setting_printer = ' . $_POST['id']);
			if ($query) {
				$message['status'] = 'ok';
				$message['message'] = 'Data berhasil diupdate';
				
			} else {
				$message['status'] = 'error';
				$message['message'] = 'Data gagal diupdate';
			}
			echo json_encode($message); 
			die;	
		}
}

function save_data() {
	
	global $db;
	
	$form_errors = validate_form();
		
	if ($form_errors) {
		$message['status'] = 'error';
		$message['message'] = $form_errors;
	} else {

		$data_db = set_data();
		if (empty($_POST['id'])) {
			
			$sql = 'SELECT COUNT(*) AS jml_data FROM setting_printer';
			$jml_data = $db->query($sql)->row();
			if ($jml_data['jml_data'] == 0) {
				$data_db['gunakan'] = 1;
			}
				
			$query = $db->insert('setting_printer', $data_db);
		} else {
			$query = $db->update('setting_printer', $data_db, 'id_setting_printer = ' . $_POST['id']);
		}
		
		if ($query) {
			$message['status'] = 'ok';
			$message['message'] = 'Data berhasil disimpan';
		} else {
			$message['status'] = 'error';
			$message['message'] = 'Data gagal disimpan';
		}
	}
	
	return $message;
}

function set_data() 
{
	$data_db['dpi'] = $_POST['dpi'];
	$data_db['margin_left'] = $_POST['margin_left'];
	$data_db['margin_top'] = $_POST['margin_top'];
	$data_db['margin_kartu_right'] = $_POST['margin_kartu_right'];
	$data_db['margin_kartu_bottom'] = $_POST['margin_kartu_bottom'];
	$data_db['margin_kartu_depan_belakang'] = $_POST['margin_kartu_depan_belakang'];
	
	return $data_db;
}

function validate_form() {
	require_once('app/libraries/FormValidation.php');
	$validation = new FormValidation();
	$validation->setRules('dpi', 'DPI', 'required');
	$validation->setRules('margin_left', 'Margin Kiri', 'required');
	$validation->setRules('margin_top', 'Margin Atas', 'required');
	$validation->setRules('margin_kartu_right', 'Margin Kartu Kanan', 'required');
	$validation->setRules('margin_kartu_bottom', 'Margin Kartu Bawah', 'required');
	$validation->setRules('margin_kartu_depan_belakang', 'Margin Kartu Depan Belakang', 'required');
	
	$validation->validate();
	$form_errors =  $validation->getMessage();
	
	return $form_errors;
}