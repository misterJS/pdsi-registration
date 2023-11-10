<?php

/**
 *	Aplikasi Cetak Kartu
 *	Developed by: Agus Prawoto Hadi
 *	Website		: www.pdsionline.org
 *	Year		: 2021
 */

$js[] = BASE_URL . 'public/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js';
$js[] = BASE_URL . 'public/themes/modern/js/date-picker.js';
$js[] = BASE_URL . 'public/themes/modern/js/image-upload.js';
$js[] = BASE_URL . 'public/vendors/datatables/dist/js/jquery.dataTables.min.js';
$js[] = BASE_URL . 'public/vendors/datatables/dist/js/dataTables.bootstrap5.min.js';
$styles[] = BASE_URL . 'public/vendors/datatables/dist/css/dataTables.bootstrap5.min.css';
$js[] = BASE_URL . 'public/themes/modern/js/daftarnama.js';
$styles[] = BASE_URL . 'public/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.css';

$site_title = 'Data Tables';

switch ($_GET['action']) {
	default:
		action_notfound();

		// INDEX 
	case 'index':

		$sql = 'SELECT * FROM siswa';
		$data['result'] = $db->query($sql)->getResultArray();

		if (!$data['result']) {
			$data['msg']['status'] = 'error';
			$data['msg']['content'] = 'Data tidak ditemukan';
		}

		load_view('views/result.php', $data);

	case 'add':

		cek_hakakses('create_data');

		$breadcrumb['Add'] = '';
		$data['title'] = 'Tambah Data Anggota';

		// Submit
		$data['msg'] = [];
		if (isset($_POST['submit'])) {
			$form_errors = validate_form();
			if (!$_FILES['foto']['name']) {
				$form_errors['foto'] = 'Foto belum dipilih';
			}

			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {

				$data_db = set_data();
				$data_db['tgl_input'] = date('Y-m-d');
				$data_db['id_user_input'] = $_SESSION['user']['id_user'];

				$path = $config['foto_path'];

				if (!is_dir($path)) {
					if (!mkdir($path, 0777, true)) {
						$data['msg']['status'] = 'error';
						$data['msg']['content'] = 'Unable to create a directory: ' . $path;
					}
				}

				$query = false;
				$new_name = upload_image($path, $_FILES['foto']);

				if ($new_name) {
					$data_db['foto'] = $new_name;
					$query = $db->insert('siswa', $data_db);

					if ($query) {
						$newid = $db->lastInsertId();
						$data['msg']['status'] = 'ok';
						$data['msg']['content'] = 'Data berhasil disimpan';
						$sql = 'SELECT foto FROM siswa WHERE id_siswa = ?';
						$result = $db->query($sql, $newid)->row();
						$data['foto'] = $result['foto'];
					} else {
						$data['msg']['status'] = 'error';
						$data['msg']['content'] = 'Data gagal disimpan';
					}
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['content'] = 'Error saat memperoses gambar';
				}
			}
		}
		load_view('views/form.php', $data);

	case 'edit':

		cek_hakakses('update_data', 'siswa');

		$breadcrumb['Edit'] = '';

		$data['title'] = 'Edit ' . $current_module['judul_module'];

		// Submit
		$data['msg'] = [];
		if (isset($_POST['submit'])) {

			$form_errors = validate_form();

			$sql = 'SELECT foto FROM siswa WHERE id_siswa = ?';
			$img_db = $db->query($sql, $_POST['id'])->row();

			if (!$_FILES['foto']['name'] && $img_db['foto'] == '') {
				$form_errors['foto'] = 'Foto belum dipilih';
			}

			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {

				$data_db = set_data();
				$data_db['tgl_edit'] = date('Y-m-d');
				$data_db['id_user_edit'] = $_SESSION['user']['id_user'];
				$path = 'public/images/foto/';

				$query = false;

				$new_name = $img_db['foto'];
				if ($_FILES['foto']['name']) {
					//old file
					if ($img_db['foto']) {
						$del = delete_file($path . $img_db['foto']);
						if (!$del) {
							$data['msg']['status'] = 'error';
							$data['msg']['content'] = 'Gagal menghapus gambar lama';
						}
					}

					$new_name = upload_image($path, $_FILES['foto'], 300, 300);
				}

				if ($new_name) {
					$data_db['foto'] = $new_name;
					$query = $db->update('siswa', $data_db, 'id_siswa = ' . $_POST['id']);
					if ($query) {
						$data['msg']['status'] = 'ok';
						$data['msg']['content'] = 'Data berhasil disimpan';
					} else {
						$data['msg']['status'] = 'error';
						$data['msg']['content'] = 'Data gagal disimpan';
					}
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['content'] = 'Error saat memperoses gambar';
				}
			}
		}

		// Updated image
		$sql = 'SELECT * FROM siswa WHERE id_siswa = ?';
		$result = $db->query($sql, trim($_GET['id']))->getRowArray();
		$data = array_merge($data, $result);
		load_view('views/form.php', $data);

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

			$val['ignore_search_action'] = '<div class="btn-group">';
			if ($list_action['update_data'] != 'no') {
				$val['ignore_search_action'] .= btn_link(['icon' => 'far fa-edit', 'attr' => ['class' => 'btn btn-success btn-xs'], 'url' => BASE_URL . $current_module['nama_module'] . '/edit?id=' . $val['id_siswa'], 'label' => 'Edit']);
			}

			if ($list_action['delete_data'] != 'no') {
				$val['ignore_search_action'] .= btn_link([
					'icon' => 'fas fa-times', 'attr' => [
						'class' => 'btn btn-danger btn-xs btn-delete', 'data-id' =>  $val['id_siswa'], 'data-delete-title' => 'Hapus data siswa: <strong>' . $val['nama'] . '</strong> ?'
					], 'url' => '#', 'label' => 'Delete'
				]);
			}

			$val['ignore_search_action'] .= '</div>';
		}

		$result['data'] = $data_table['content'];
		echo json_encode($result);
		exit();

	case 'ajaxDeleteData':
		cek_hakakses('delete_data', 'siswa');

		$sql = 'SELECT foto FROM siswa WHERE id_siswa = ?';
		$img = $db->query($sql, $_POST['id'])->getRowArray();
		if ($img['foto']) {
			$del = delete_file($config['foto_path'] . $img['foto']);
			if (!$del) {
				return false;
			}
		}

		$result = $db->delete('siswa', ['id_siswa' => $_POST['id']]);
		// $result = true;
		if ($result) {
			$message = ['status' => 'ok', 'message' => 'Data siswa berhasil dihapus'];
		} else {
			$message = ['status' => 'error', 'message' => 'Data siswa gagal dihapus'];
		}

		echo json_encode($message);
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
	$exp = explode('-', $_POST['tgl_lahir']);
	$tgl_lahir = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
	$data_db['nama'] = $_POST['nama'];
	$data_db['jenis_kelamin'] = $_POST['jenis_kelamin'];
	$data_db['email'] = $_POST['email'];
	$data_db['tempat_lahir'] = $_POST['tempat_lahir'];
	$data_db['tgl_lahir'] = $tgl_lahir;
	$data_db['alamat'] = $_POST['alamat'];
	$data_db['province'] = $_POST['province'];
	return $data_db;
}

function validate_form()
{

	require_once('app/libraries/FormValidation.php');
	$validation = new FormValidation();
	$validation->setRules('nama', 'Nama Siswa', 'required');
	$validation->setRules('email', 'Email', 'trim|required');
	$validation->setRules('tempat_lahir', 'Tempat Lahir', 'trim|required');
	$validation->setRules('tgl_lahir', 'Tanggal Lahir', 'trim|required');
	$validation->setRules('alamat', 'Alamat', 'trim|required');

	$validation->validate();
	$form_errors =  $validation->getMessage();

	if ($_FILES['foto']['name']) {

		$file_type = $_FILES['foto']['type'];
		$allowed = ['image/png', 'image/jpeg', 'image/jpg'];

		if (!in_array($file_type, $allowed)) {
			$form_errors['foto'] = 'Tipe file harus ' . join($allowed, ['']);
		}

		if ($_FILES['foto']['size'] > 1024 * 1024) {
			$form_errors['foto'] = 'Ukuran file maksimal 1Mb';
		}

		$info = getimagesize($_FILES['foto']['tmp_name']);
		if ($info[0] < 100 || $info[1] < 100) { //0 Width, 1 Height
			$form_errors['foto'] = 'Dimensi file minimal: 100px x 100px';
		}
	}

	return $form_errors;
}
