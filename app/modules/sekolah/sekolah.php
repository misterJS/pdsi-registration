<?php

/**
 *	Aplikasi Cetak Kartu
 *	Developed by: Agus Prawoto Hadi
 *	Website		: www.jagowebdev.com
 *	Year		: 2021
 */

$site_title = 'Data Pendidikan';
$js[] = THEME_URL . 'js/image-upload.js';
$js[] = BASE_URL . 'public/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js';
$js[] = BASE_URL . 'public/themes/modern/js/date-picker.js';
$js[] = BASE_URL . 'public/themes/modern/js/image-upload.js';
$js[] = BASE_URL . 'public/vendors/datatables/dist/js/jquery.dataTables.min.js';
$js[] = BASE_URL . 'public/vendors/datatables/dist/js/dataTables.bootstrap5.min.js';
$styles[] = BASE_URL . 'public/vendors/datatables/dist/css/dataTables.bootstrap5.min.css';
$styles[] = BASE_URL . 'public/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.css';

switch ($_GET['action']) {
	default:
		action_notfound();

		// INDEX 
	case 'index':

		if (!empty($_POST['delete'])) {
			$result = $db->delete('sekolah', ['id_sekolah' => $_POST['id']]);
			// $result = true;
			if ($result) {
				$data['msg'] = ['status' => 'ok', 'message' => 'Data Pendidikan berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'error', 'message' => 'Data Pendidikan gagal dihapus'];
			}
		}
		$sql = 'SELECT * FROM sekolah';
		if (isset($_SESSION['user']['role']['4'])) {
			$sql = 'SELECT * FROM sekolah WHERE email = ?';
			$data['result'] = $db->query($sql, $_SESSION['user']['email'])->result();
		} else {
			$sql = 'SELECT * FROM sekolah';
			$data['result'] = $db->query($sql)->result();
		}

		if (!$data['result']) {
			$data['msg']['status'] = 'error';
			$data['msg']['content'] = 'Data tidak ditemukan';
		}

		load_view('views/result.php', $data);

	case 'edit':

		$data['title'] = 'Edit Data Pendidikan';
		$breadcrumb['Add'] = '';

		// Submit
		$data['msg'] = [];
		if (isset($_POST['submit'])) {
			require_once('app/libraries/FormValidation.php');
			$validation = new FormValidation();
			$validation->setRules('nama_sekolah', 'Nama Sekolah', 'required');

			$validation->validate();
			$form_errors =  $validation->getMessage();

			if ($_POST['id']) {
				$sql = 'SELECT ijazah FROM sekolah WHERE id_sekolah = ?';
				$img_db = $db->query($sql, $_POST['id'])->row();
			}

			// $form_errors = [];
			if (!$_FILES['ijazah']['name']) {

				if ($_POST['id'] && $img_db['ijazah'] == '') {
					$form_errors['ijazah'] = 'Ijazah belum dipilih';
				}
			} else {

				$file_type = $_FILES['ijazah']['type'];
				$allowed = ['application/pdf', 'image/png'];

				if (!in_array($file_type, $allowed)) {
					$form_errors['ijazah'] = 'Tipe file harus ' . join($allowed, null);
				}

				if ($_FILES['ijazah']['size'] > 2000 * 1024) {
					$form_errors['ijazah'] = 'Ukuran file maksimal 2MB';
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
				$data_db['jenjang'] = $_POST['jenjang'];
				$data_db['alamat'] = $_POST['alamat'];
				$data_db['tlp_fax'] = $_POST['tlp_fax'];
				$data_db['website'] = $_POST['website'];
				$data_db['email'] = $_SESSION['user']['email'];

				$path = $config['kartu_path'];

				$query = false;
				// EDIT
				if (!empty($_POST['id'])) {
					$new_name = $img_db['ijazah'];
					if ($_FILES['ijazah']['name']) {
						//old file
						if ($img_db['ijazah']) {
							$del = delete_file($path . $img_db['ijazah']);
							if (!$del) {
								$data['msg']['status'] = 'error';
								$data['msg']['content'] = 'Gagal menghapus ijazah lama';
							}
						}

						$new_name = upload_file($path, $_FILES['ijazah']);
					}

					if ($new_name) {
						$data_db['ijazah'] = $new_name;

						$query = $db->update('sekolah', $data_db, 'id_sekolah = ' . $_POST['id']);
					} else {
						$data['msg']['status'] = 'error';
						$data['msg']['content'] = 'Error saat memperoses ijazah';
					}
				} else {

					// Add
					if (!is_dir($path)) {
						if (!mkdir($path, 0777, true)) {
							$data['msg']['status'] = 'error';
							$data['msg']['content'] = 'Unable to create a directory: ' . $path;
						}
					} else {

						$new_name = upload_file($path, $_FILES['ijazah']);
						if ($new_name) {
							$data_db['ijazah'] = $new_name;
							$query = $db->insert('sekolah', $data_db);
						} else {
							$data['msg']['status'] = 'error';
							$data['msg']['content'] = 'Error saat memperoses ijazah';
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

				$data['title'] = 'Edit Data Pendidikan';
			}
		}

		if (!empty($_GET['id'])) {
			$sql = 'SELECT * FROM sekolah WHERE id_sekolah = ?';
			$result = $db->query($sql, trim($_GET['id']))->result();
			$data	= array_merge($data, $result[0]);

			$data['title'] = 'Edit Data Pendidikan';
		}

		load_view('views/form.php', $data);
}
