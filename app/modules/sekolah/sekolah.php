<?php
/**
*	Aplikasi Cetak Kartu
*	Developed by: Agus Prawoto Hadi
*	Website		: www.jagowebdev.com
*	Year		: 2021
*/

$site_title = 'Data Sekolah';
$js[] = THEME_URL . 'js/image-upload.js';

switch ($_GET['action']) 
{
	default: 
		action_notfound();
		
	// INDEX 
	case 'index':
		
		if (!empty($_POST['delete'])) 
		{
			$result = $db->delete('sekolah', ['id_sekolah' => $_POST['id']]);
			// $result = true;
			if ($result) {
				$data['msg'] = ['status' => 'ok', 'message' => 'Data gedung berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'error', 'message' => 'Data gedung gagal dihapus'];
			}
		}
		$sql = 'SELECT * FROM sekolah';
		$data['result'] = $db->query($sql)->result();
		
		if (!$data['result']) {
			$data['msg']['status'] = 'error';
			$data['msg']['content'] = 'Data tidak ditemukan';
		}
		
		load_view('views/result.php', $data);
	
	case 'edit': 
			
		$data['title'] = 'Edit Data sekolah';
		$breadcrumb['Add'] = '';
			
		// Submit
		$data['msg'] = [];
		if (isset($_POST['submit'])) 
		{
			require_once('app/libraries/FormValidation.php');
			$validation = new FormValidation();
			$validation->setRules('nama_sekolah', 'Nama Sekolah', 'required');
			$validation->setRules('nama_dinas', 'Nama Dinas', 'trim|required');
			$validation->setRules('alamat', 'Alamat', 'trim|required');
			$validation->setRules('tlp_fax', 'Telp/Fax', 'trim|required');
			$validation->setRules('website', 'Website', 'trim|required');
			
			$validation->validate();
			$form_errors =  $validation->getMessage();
			
			if ($_POST['id']) {
				$sql = 'SELECT logo FROM sekolah WHERE id_sekolah = ?';
				$img_db = $db->query($sql, $_POST['id'])->row();
			}
					
			// $form_errors = [];
			if (!$_FILES['logo']['name']) {
				
				if ($_POST['id'] && $img_db['logo'] == '') {
					$form_errors['logo'] = 'Logo belum dipilih';
				}
			} else {
				
				$file_type = $_FILES['logo']['type'];
				$allowed = ['image/png', 'image/jpeg', 'image/jpg'];
				
				if (!in_array($file_type, $allowed)) {
					$form_errors['logo'] = 'Tipe file harus ' . join($allowed, ', ');
				}
				
				if ($_FILES['logo']['size'] > 300 * 1024) {
					$form_errors['logo'] = 'Ukuran file maksimal 300Kb';
				}
			}
			
			// $merge_valid = array_merge($form_errors, $valid);
			
			// echo '<pre>'; print_r($form_errors); die;
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {
		
				$data_db['nama_sekolah'] = $_POST['nama_sekolah'];
				$data_db['nama_dinas'] = $_POST['nama_dinas'];
				$data_db['alamat'] = $_POST['alamat'];
				$data_db['tlp_fax'] = $_POST['tlp_fax'];
				$data_db['website'] = $_POST['website'];
				
				$path = $config['kartu_path'];
				
				$query = false;
				// EDIT
				if (!empty($_POST['id'])) 
				{
					$new_name = $img_db['logo'];
					if ($_FILES['logo']['name']) 
					{
						//old file
						if ($img_db['logo']) {
							$del = delete_file($path . $img_db['logo']);
							if (!$del) {
								$data['msg']['status'] = 'error';
								$data['msg']['content'] = 'Gagal menghapus gambar lama';
							}
						}
						
						$new_name = upload_file($path, $_FILES['logo']);
					}
		
					if ($new_name) {
						$data_db['logo'] = $new_name;
						
						$query = $db->update('sekolah', $data_db, 'id_sekolah = ' . $_POST['id']);
					} else {
						$data['msg']['status'] = 'error';
						$data['msg']['content'] = 'Error saat memperoses gambar';
					}
					
				} else {
					
				// Add
					if (!is_dir($path)) {
						if (!mkdir($path, 0777, true)) {
							$data['msg']['status'] = 'error';
							$data['msg']['content'] = 'Unable to create a directory: ' . $path;
						}
					} else {

						$new_name = upload_file($path, $_FILES['logo']);
						if ($new_name) {
							$data_db['logo'] = $new_name;
							$query = $db->insert('sekolah', $data_db);
						} else {
							$data['msg']['status'] = 'error';
							$data['msg']['content'] = 'Error saat memperoses gambar';
						}
					}
				}
				
				if ($query) {
					$data['msg']['status'] = 'ok';
					$data['msg']['content'] = 'Data berhasil disimpan';
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['content'] = 'Data gagal disimpan';
				}
				
				$data['title'] = 'Edit Data Sekolah';
			}
		}
		
		if (!empty($_GET['id'])) 
		{
			$sql = 'SELECT * FROM sekolah WHERE id_sekolah = ?';
			$result = $db->query($sql, trim($_GET['id']))->result();
			$data	= array_merge($data, $result[0]);

			$data['title'] = 'Edit Data Sekolah';
		}
		
		load_view('views/form.php', $data);
}