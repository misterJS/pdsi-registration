<?php
/**
*	Aplikasi Cetak Kartu
*	Developed by: Agus Prawoto Hadi
*	Website		: www.pdsionline.org
*	Year		: 2021
*/

$js[] = BASE_URL . 'public/vendors/dragula/dragula.min.js';
$styles[] = BASE_URL . 'public/vendors/dragula/dragula.min.css';
$styles[] = BASE_URL . 'public/vendors/flatpickr/dist/flatpickr.min.css';

$js[] = BASE_URL . 'public/vendors/datatables/dist/js/jquery.dataTables.min.js';
$js[] = BASE_URL . 'public/vendors/datatables/dist/js/dataTables.bootstrap5.min.js';
$styles[] = BASE_URL . 'public/vendors/datatables/dist/css/dataTables.bootstrap5.min.css';

$js[] = BASE_URL . 'public/themes/modern/js/set-default.js';
$js[] = THEME_URL . 'js/image-upload.js';
$js[] = THEME_URL . 'js/layoutkartu.js';
$js[] = THEME_URL . 'js/layoutkartu-image-upload.js';
$js[] = BASE_URL . 'public/vendors/flatpickr/dist/flatpickr.js';

$site_title = 'Layout Kartu';
$styles[] = BASE_URL . 'public/themes/modern/css/desainkartu.css';

function set_data($id = null) {
	global $setting_web, $current_module, $db;

	$data['setting_web'] = $setting_web;
	$data['app_module'] = $current_module;
	
	$data['setting_kartu'] = [];
	
	if ($id) {
		$sql = 'SELECT * FROM setting_kartu WHERE id_setting_kartu = ' . $id;
		$result = $db->query($sql)->row();
		$data['setting_kartu']	= $result;
		
		$sql = 'SELECT * FROM siswa_data_digunakan WHERE id_Setting_kartu = ' . $id . ' ORDER BY urut';
		$data['siswa_data_digunakan'] = $db->query($sql)->getResultArray();
		
	} else {
		$fields = $db->getField('setting_kartu');
		foreach ($fields as $column => $val) {
			$data['setting_kartu'][$column] = '';
		}
		
	}

	/* $sql = 'SELECT * FROM setting_qrcode';
	$result = $db->query($sql)->row();
	$data['qrcode']	= $result; */
	
	$sql = 'SELECT * FROM setting_printer WHERE gunakan = 1';
	$result = $db->query($sql)->row();
	$data['printer']	= $result;
	
	$sql = 'SELECT * FROM siswa LIMIT 1';
	$siswa = $db->query($sql)->getRowArray();
	$data['siswa'] = $siswa;
	
	$fields = $db->getField('siswa');
	foreach ($fields as $val) {			
		$data['fields'][$val['column_name']] = $data['siswa'][$val['column_name']];
	}
	
	$fields = $db->getField('siswa');
	foreach ($fields as $val) {			
		$data['field_table'][$val['column_name']] = $val['column_name'];
	}

	return $data;
}

$data = set_data();
$data['title'] = 'Desain Kartu';

switch ($_GET['action']) 
{
	default: 
		action_notfound();
		
	// INDEX 
	case 'index':
		
		load_view('views/result.php', $data);
		
	case 'delete' :
		if (!empty($_POST['delete'])) 
		{
			$sql = 'SELECT * FROM setting_kartu WHERE id_setting_kartu = ' . $_POST['id'];
			$data_kartu = $db->query($sql)->getRowArray();
			
			$db->beginTrans();
			$result = $db->delete('setting_kartu', ['id_setting_kartu' => $_POST['id']]);
			if ($result) {
				delete_file (BASE_PATH . 'public/images/kartu/' . $data_kartu['background_depan']);
				delete_file (BASE_PATH . 'public/images/kartu/' . $data_kartu['background_belakang']);
				delete_file (BASE_PATH . 'public/images/kartu/' . $data_kartu['ttd_cap_image']);
				delete_file (BASE_PATH . 'public/images/kartu/' . $data_kartu['ttd_sign_image']);
				
				$db->delete('siswa_data_digunakan', ['id_setting_kartu' => $_POST['id']]);
								
				$sql = 'SELECT COUNT(*) AS jml_data FROM setting_kartu WHERE gunakan = ' . $_POST['id'];
				$jml_data = $db->query($sql)->row();
				if ($jml_data['jml_data'] == 0) {
					$sql = 'SELECT id_setting_kartu FROM setting_kartu ORDER BY id_setting_kartu DESC LIMIT 1';
					$id = $db->query($sql)->row();
					if ($id) {
						$data_db['gunakan'] = 1;
						$query = $db->update('setting_kartu', $data_db, 'id_setting_kartu = ' . $id['id_setting_kartu']);
					}
				}
				
				$query = $db->completeTrans();
				if ($query) {
					$message = ['status' => 'ok', 'message' => 'Data berhasil dihapus'];
				} else {
					$message = ['status' => 'error', 'message' => 'Data gagal dihapus'];
				}
			} else {
				$message = ['status' => 'error', 'message' => 'Data gagal dihapus'];
			}
			
			echo json_encode($message);
			exit;
		}
		
		break;
	
	case 'preview-qrcode':
		
		if ($_GET['qrcode_content_jenis'] == 'field_database') {
			$field_database = $_GET['qrcode_content_field_database'] ;
			$sql = 'SELECT ' . $field_database . ' FROM siswa LIMIT 1';
			$result = $db->query($sql)->getRowArray();
			$content = $result[$field_database];
		} else {
			$content = $_GET['qrcode_content_global_text'];
		}
		
		if (!trim($content)) {
			echo '<div class="alert alert-warning">Data QR Code masih kosong</div>';
			exit;
		}
		
		require BASE_PATH . 'app/libraries' . DS . 'vendors' . DS . 'qrcode' . DS . 'qrcode_extended.php';
		if (is_int($_GET['qrcode_size_module'])) {
			$height = $_GET['qrcode_size_module'] % 2 ? $_GET['qrcode_size_module'] : $_GET['qrcode_size_module'] + 0.5;
		} else {
			$height = $_GET['qrcode_size_module'];
		}

		$qr = new QRCodeExtended();
		
		$ecc = ['L' => QR_ERROR_CORRECT_LEVEL_L
				, 'M' => QR_ERROR_CORRECT_LEVEL_M
				, 'Q' => QR_ERROR_CORRECT_LEVEL_Q
				, 'H' => QR_ERROR_CORRECT_LEVEL_H
			];
		$qr->setErrorCorrectLevel($ecc[$_GET['qrcode_ecc_level']]);
		$qr->setTypeNumber($_GET['qrcode_version']);
		$qr->addData($content);
		$cek = $qr->checkError();
		if ($cek['status'] == 'success') {
			$qr->make();
			echo $qr->saveHtml($_GET['qrcode_size_module']);
		} else {
			echo '<div class="alert alert-warning">' . $cek['content'] . '</div>';
		}
		die;
	
	case 'add':
		
		if (isset($_POST['submit'])) 
		{
			$message = save_data();
			echo json_encode($message);
			exit;
		}
		
		$breadcrumb['Add'] = '';
		$data['title'] = 'Tambah Data';
		load_view('views/form.php', $data);
		
	case 'edit': 
		
		// Submit
		if (isset($_POST['submit'])) 
		{
			$message = save_data();
			
			echo json_encode($message);
			exit();
		}
		
		$breadcrumb['Edit'] = '';
		$data = set_data($_GET['id']);
		
		if (!$data['setting_kartu']) {
			$message['status'] = 'error';
			$message['content'] = 'Data tidak ditemukan';
		}
		
		$sql = 'SELECT * FROM siswa_data_digunakan WHERE id_setting_kartu = ' . $_GET['id'] . ' ORDER BY urut';
		$data['siswa_data_digunakan'] = $db->query($sql)->getResultArray();
				
		$fields = $db->getField('siswa');
		foreach ($fields as $val) {			
			$data['fields'][$val['column_name']] = $data['siswa'][$val['column_name']];
		}
		
		$data['title'] = 'Desain Kartu';
		load_view('views/form.php', $data);
	
	case 'getDataDT':
				
		$result['draw'] = $start = $_POST['draw'] ?: 1;
		
		$data_table = getListData();
		$result['recordsTotal'] = $data_table['total_data'];
		$result['recordsFiltered'] = $data_table['total_filtered'];
				
		helper('html');
		$id_user = $_SESSION['user']['id_user'];
		
		foreach ($data_table['content'] as $key => &$val) 
		{
			$checked = $val['gunakan'] == 'Y' ? ' checked="checked"' : '';
			$val['gunakan'] = '<div class="form-switch">
								<input name="gunakan" type="checkbox" class="form-check-input switch switch-gunakan" data-id="'.$val['id_setting_kartu'].'" ' . $checked . '>
							</div>';
							
			if ( $val['background_depan'] ) {
				$val['background_depan'] = '<div style="max-width: 200px"><img style="width:100%;max-width:120px" src="' . BASE_URL . 'public/images/kartu/' . $val['background_depan'] . '"/></div>';
			}
			
			if ( $val['background_belakang'] ) {
				$val['background_belakang'] = '<div style="max-width: 200px"><img style="width:100%;max-width:120px" src="' . BASE_URL . 'public/images/kartu/' . $val['background_belakang'] . '"/></div>';
			}
			
			$val['ignore_action'] = '<div class="btn-group">
										<a href="' . BASE_URL . 'desainkartu/edit?id='. $val['id_setting_kartu'] . '" target="_blank" class="btn btn-xs btn-success"><i class="fas fa-edit me-2"></i>Edit</a>
										<button type="button" class="btn btn-xs btn-delete-setting btn-danger" data-id="' .  $val['id_setting_kartu'] . '"><i class="fas fa-times me-2"></i>Delete</button>
									</div>';
		}
					
		$result['data'] = $data_table['content'];
		echo json_encode($result); 
		exit();
		
	case 'set-default':
		if (isset($_POST['submit'])) 
		{
			$data_db['gunakan'] = 'N';
			$query = $db->update('setting_kartu', $data_db);
			$query = $db->update('setting_kartu', ['gunakan' => 'Y'], 'id_setting_kartu = ' . $_POST['id']);
			if ($query) {
				$message['status'] = 'ok';
				$message['content'] = 'Data berhasil diupdate';
				
			} else {
				$message['status'] = 'error';
				$message['content'] = 'Data gagal diupdate';
			}
			
			echo json_encode($message); 
			die;	
		}
}

function save_data() {
	
	global $config, $db;
	// Check Error
	$form_errors = validate_form();
	
	// File Image
	$img_db['background_depan'] = $img_db['background_belakang'] = $img_db['ttd_sign_image'] = $img_db['ttd_cap_image'] = '';
	if ($_POST['id']) {
		$sql = 'SELECT * FROM setting_kartu WHERE id_setting_kartu = ?';
		$query_img = $db->query($sql, $_POST['id'])->row();
		$img_db['background_depan'] = $query_img['background_depan'];
		$img_db['background_belakang'] = $query_img['background_belakang'];
		$img_db['ttd_sign_image'] = $query_img['ttd_sign_image'];
		$img_db['ttd_cap_image'] = $query_img['ttd_cap_image'];
	}
	
	/* if (!$_FILES['background_depan']['name']) {
		if ($img_db['background_depan'] == '') {
			$form_errors['background_depan'] = 'Background depan belum dipilih';
		}
	} 
	
	if (!$_FILES['background_belakang']['name']) {
		if ($img_db['background_belakang'] == '') {
			$form_errors['background_belakang'] = 'Background belakang belum dipilih';
		}
	}
	
	if ($_POST['ttd_gunakan'] == 'Y') {
		if (!$_FILES['ttd_sign_image']['name']) {
			if ($img_db['ttd_sign_image'] == '') {
				$form_errors['ttd_sign_image'] = 'Gambar tanda tangan belum dipilih';
			}
		}
		
		if (!$_FILES['ttd_cap_image']['name']) {
			if ($img_db['ttd_cap_image'] == '') {
				$form_errors['ttd_cap_image'] = 'Gambar stempel belum dipilih';
			}
		}	
	} */

	if ($form_errors) {
		$message['status'] = 'error';
		$message['message'] = $form_errors;
	} else {

		$data_db = set_datadb();
			
		$path = $config['kartu_path'];
		$query = false;
		
		// Depan
		$new_kartu_depan = !empty($img_db) ? $img_db['background_depan'] : '';
		if ($_FILES['background_depan']['name']) 
		{
			//old file
			if ($img_db['background_depan']) {
				$del = delete_file($path . $img_db['background_depan']);
				if (!$del) {
					$message['status'] = 'error';
					$message['message'] = 'Gagal menghapus gambar lama';
				}
			}
			$new_kartu_depan = upload_image($path, $_FILES['background_depan']);
		}
		
		// Belakang
		$new_kartu_belakang = !empty($img_db) ? $img_db['background_belakang'] : '';
		if ($_FILES['background_belakang']['name']) 
		{
			//old file
			if ($img_db['background_belakang']) {
				$del = delete_file($path . $img_db['background_belakang']);
				if (!$del) {
					$message['status'] = 'error';
					$message['message'] = 'Gagal menghapus gambar lama';
				}
			}
			
			$new_kartu_belakang = upload_image($path, $_FILES['background_belakang']);
		}
		
		// Tanda Tangan
		$new_ttd_sign_image = $new_ttd_cap_image = '';
		
		if ($img_db) {
			$new_ttd_sign_image = $img_db['ttd_sign_image'];
			$new_ttd_cap_image = $img_db['ttd_cap_image'];
		}
		
		if ($_POST['ttd_gunakan'] == 'Y') 
		{
			
			if ($_FILES['ttd_sign_image']['name']) 
			{
				//old file
				if ($img_db['ttd_sign_image']) {
					$del = delete_file($path . $img_db['ttd_sign_image']);
					if (!$del) {
						$message['status'] = 'error';
						$message['message'] = 'Gagal menghapus gambar lama';
					}
				}
				
				$new_ttd_sign_image = upload_image($path, $_FILES['ttd_sign_image']);
			}
				
			if ($_FILES['ttd_cap_image']['name']) 
			{
				//old file
				if ($img_db['ttd_cap_image']) {
					$del = delete_file($path . $img_db['ttd_cap_image']);
					if (!$del) {
						$message['status'] = 'error';
						$message['message'] = 'Gagal menghapus gambar lama';
					}
				}
				
				$new_ttd_cap_image = upload_image($path, $_FILES['ttd_cap_image']);
			}
		}
		

		$db->beginTrans();
		
		$data_db['background_depan'] = $new_kartu_depan;
		$data_db['background_belakang'] = $new_kartu_belakang;
		$data_db['ttd_sign_image'] = $new_ttd_sign_image;
		$data_db['ttd_cap_image'] = $new_ttd_cap_image;
		
		if (!empty($_POST['id'])) {
			$id_setting_kartu = $_POST['id'];
			$query = $db->update('setting_kartu', $data_db, 'id_setting_kartu = ' . $_POST['id']);
		} else {
			$query = $db->insert('setting_kartu', $data_db);
			$id_setting_kartu = $db->lastInsertId();
		}
		
		$urut = 1;
		foreach ($_POST['pattern'] as $index => $val) {
			$data_db_pattern[] = ['id_setting_kartu' => $id_setting_kartu, 'pattern' => $val, 'judul_data' => $_POST['judul_data'][$index], 'urut' => $urut];
			$urut++;
		}
		$query = $db->delete('siswa_data_digunakan', ['id_setting_kartu' => $_POST['id']]);
		$query = $db->insertBatch('siswa_data_digunakan', $data_db_pattern);
			
		$query = $db->completeTrans();
		
		if ($query) {
			$message['status'] = 'ok';
			$message['message'] = 'Data berhasil disimpan';
			$message['id'] = $id_setting_kartu;
		} else {
			$message['status'] = 'error';
			$message['message'] = 'Data gagal disimpan';
		}
	}
	
	return $message;
}
function set_datadb() {

	$data_db['nama_setting'] = $_POST['nama_setting'];
	$data_db['gunakan'] = $_POST['gunakan'];
    $data_db['kartu_width'] = $_POST['kartu_width'];
    $data_db['kartu_height'] = $_POST['kartu_height'];
    $data_db['data_depan_margin_left'] = $_POST['data_depan_margin_left'];
    $data_db['data_depan_margin_top'] = $_POST['data_depan_margin_top'];
    $data_db['data_depan_show_label'] = $_POST['data_depan_show_label'];
    $data_db['data_depan_label_width'] = $_POST['data_depan_label_width'];
    $data_db['data_depan_line_height'] = $_POST['data_depan_line_height'];
    $data_db['data_depan_font_family'] = $_POST['data_depan_font_family'];
    $data_db['data_depan_font_size'] = $_POST['data_depan_font_size'];
    $data_db['data_depan_text_align'] = $_POST['data_depan_text_align'];
    $data_db['data_depan_font_weight'] = $_POST['data_depan_font_weight'];
    $data_db['foto_width'] = $_POST['foto_width'];
    $data_db['foto_height'] = $_POST['foto_height'];
    $data_db['foto_margin_left'] = $_POST['foto_margin_left'];
    $data_db['foto_margin_top'] = $_POST['foto_margin_top'];
    $data_db['ttd_text'] = $_POST['ttd_text'];
    $data_db['ttd_gunakan'] = $_POST['ttd_gunakan'];
    $data_db['ttd_posisi'] = $_POST['ttd_posisi'];
    $data_db['ttd_text_margin_left'] = $_POST['ttd_text_margin_left'];
    $data_db['ttd_text_margin_top'] = $_POST['ttd_text_margin_top'];
    $data_db['ttd_text_space_sign'] = $_POST['ttd_text_space_sign'];
    $data_db['ttd_text_align'] = $_POST['ttd_text_align'];
    $data_db['ttd_text_font_family'] = $_POST['ttd_text_font_family'];
    $data_db['ttd_text_font_size'] = $_POST['ttd_text_font_size'];
    $data_db['ttd_text_font_weight'] = $_POST['ttd_text_font_weight'];
    $data_db['ttd_sign_width'] = $_POST['ttd_sign_width'];
    $data_db['ttd_sign_height'] = $_POST['ttd_sign_height'];
    $data_db['ttd_sign_margin_left'] = $_POST['ttd_sign_margin_left'];
    $data_db['ttd_sign_margin_top'] = $_POST['ttd_sign_margin_top'];
    $data_db['ttd_cap_width'] = $_POST['ttd_cap_width'];
    $data_db['ttd_cap_height'] = $_POST['ttd_cap_height'];
    $data_db['ttd_cap_margin_left'] = $_POST['ttd_cap_margin_left'];
    $data_db['ttd_cap_margin_top'] = $_POST['ttd_cap_margin_top'];
    $data_db['qrcode_gunakan'] = $_POST['qrcode_gunakan'];
    $data_db['qrcode_posisi'] = $_POST['qrcode_posisi'];
    $data_db['qrcode_version'] = $_POST['qrcode_version'];
    $data_db['qrcode_ecc_level'] = $_POST['qrcode_ecc_level'];
    $data_db['qrcode_size_module'] = $_POST['qrcode_size_module'];
    $data_db['qrcode_padding'] = $_POST['qrcode_padding'];
    $data_db['qrcode_margin_left'] = $_POST['qrcode_margin_left'];
    $data_db['qrcode_margin_top'] = $_POST['qrcode_margin_top'];
    $data_db['qrcode_content_jenis'] = $_POST['qrcode_content_jenis'];
    $data_db['qrcode_content_field_database'] = $_POST['qrcode_content_field_database'];
    $data_db['qrcode_content_global_text'] = $_POST['qrcode_content_global_text'];
    $data_db['berlaku_gunakan'] = $_POST['berlaku_gunakan'];
    $data_db['berlaku_posisi'] = $_POST['berlaku_posisi'];
    $data_db['berlaku_margin_left'] = $_POST['berlaku_margin_left'];
    $data_db['berlaku_margin_top'] = $_POST['berlaku_margin_top'];
    $data_db['berlaku_jenis'] = $_POST['berlaku_jenis'];
    $data_db['berlaku_periode_prefix'] = $_POST['berlaku_periode_prefix'];
    $data_db['berlaku_custom_text'] = $_POST['berlaku_custom_text'];
	
	if ($_POST['berlaku_hingga_tanggal']) {
		$exp = explode('-', $_POST['berlaku_hingga_tanggal']);
		$berlaku_hingga_tanggal = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
	} else {
		$berlaku_hingga_tanggal = null;
	}
    $data_db['berlaku_hingga_tanggal'] = $berlaku_hingga_tanggal;
		
	return $data_db;
}

function getListData() {
	
	global $db;
	$columns = $_POST['columns'];
	$order_by = '';
	
	// Search
	$search_all = @$_POST['search']['value'];
	$where = where_own();
	if ($search_all) {

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
	
	if (@$order[0]['column'] != '' ) {
		$order_by = ' ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']);
	}

	$start = $_POST['start'] ?: 0;
	$length = $_POST['length'] ?: 10;
	
	// Query Total
	$sql = 'SELECT COUNT(*) AS jml_data FROM setting_kartu' . where_own();
	$query = $db->query($sql)->getRowArray();
	$total_data = $query['jml_data'];
	
	// Query Filtered
	$sql = 'SELECT COUNT(*) AS jml_data FROM setting_kartu' . $where;
	$query = $db->query($sql)->getRowArray();
	$total_filtered = $query['jml_data'];
	
	// Query Data
	$sql = 'SELECT * FROM setting_kartu 
			' . $where . $order_by . ' LIMIT ' . $start . ', ' . $length;
	$content = $db->query($sql)->getResultArray();
	
	return ['total_data' => $total_data, 'total_filtered' => $total_filtered, 'content' => $content];
}

function validate_form() 
{
	require_once('app/libraries/FormValidation.php');
	$validation = new FormValidation();
	$validation->setRules('nama_setting', 'Nama Setting', 'required');
	
	if ($_POST['qrcode_gunakan'] == 'Y') {
		if ($_POST['berlaku_jenis'] == 'custom_text') {
			$validation->setRules('berlaku_custom_text', 'Custom Text', 'required|trim|min_length[10]');
		} else {
			$validation->setRules('berlaku_periode_prefix', 'Periode Prefix', 'required|trim|min_length[5]');
		}
	}
	
	$validation->validate();
	$form_errors =  $validation->getMessage();
					
	if ($_FILES['background_depan']['name']) {
		
		$file_type = $_FILES['background_depan']['type'];
		$allowed = ['image/png', 'image/jpeg', 'image/jpg'];
		
		if (!in_array($file_type, $allowed)) {
			$form_errors['background_depan'] = 'Tipe file harus ' . join($allowed, ', ');
		}
		
		if ($_FILES['background_depan']['size'] > 1024 * 1024) {
			$form_errors['background_depan'] = 'Ukuran file maksimal 1Mb';
		}
		
		$info = getimagesize($_FILES['background_depan']['tmp_name']);
		if ($info[0] < 100 || $info[1] < 100) { //0 Width, 1 Height
			$form_errors['background_depan'] = 'Dimensi file minimal: 100px x 100px';
		}
	}
	
	if ($_FILES['background_belakang']['name']) {
		
		$file_type = $_FILES['background_belakang']['type'];
		$allowed = ['image/png', 'image/jpeg', 'image/jpg'];
		
		if (!in_array($file_type, $allowed)) {
			$form_errors['background_belakang'] = 'Tipe file harus ' . join($allowed, ', ');
		}
		
		if ($_FILES['background_belakang']['size'] > 1024 * 1024) {
			$form_errors['background_belakang'] = 'Ukuran file maksimal 1Mb';
		}
		
		$info = getimagesize($_FILES['background_belakang']['tmp_name']);
		if ($info[0] < 100 || $info[1] < 100) { //0 Width, 1 Height
			$form_errors['background_belakang'] = 'Dimensi file minimal: 100px x 100px';
		}
	}
	
	if ($_POST['ttd_gunakan'] == 'Y') {
		if ($_FILES['ttd_sign_image']['name']) {
			
			$file_type = $_FILES['ttd_sign_image']['type'];
			$allowed = ['image/png', 'image/jpeg', 'image/jpg'];
			
			if (!in_array($file_type, $allowed)) {
				$form_errors['ttd_sign_image'] = 'Tipe file harus ' . join($allowed, ', ');
			}
			
			if ($_FILES['ttd_sign_image']['size'] > 1024 * 1024) {
				$form_errors['ttd_sign_image'] = 'Ukuran file maksimal 1Mb';
			}
			
			$info = getimagesize($_FILES['ttd_sign_image']['tmp_name']);
			if ($info[0] < 50 || $info[1] < 50) { //0 Width, 1 Height
				$form_errors['ttd_sign_image'] = 'Dimensi file minimal: 50px x 50px';
			}
		}
		
		if ($_FILES['ttd_cap_image']['name']) {
			
			$file_type = $_FILES['ttd_cap_image']['type'];
			$allowed = ['image/png', 'image/jpeg', 'image/jpg'];
			
			if (!in_array($file_type, $allowed)) {
				$form_errors['ttd_cap_image'] = 'Tipe file harus ' . join($allowed, ', ');
			}
			
			if ($_FILES['ttd_cap_image']['size'] > 1024 * 1024) {
				$form_errors['ttd_cap_image'] = 'Ukuran file maksimal 1Mb';
			}
			
			$info = getimagesize($_FILES['ttd_cap_image']['tmp_name']);
			if ($info[0] < 50 || $info[1] < 50) { //0 Width, 1 Height
				$form_errors['ttd_cap_image'] = 'Dimensi file minimal: 50px x 50px';
			}
		}
	}
	return $form_errors;
}