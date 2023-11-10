<?php
/**
*	PHP *	Year		: 2022
*	Developed by: Agus Prawoto Hadi
*	Website		: https://pdsionline.org
*	Year		: 2022
*/

$js[] = BASE_URL . 'public/themes/modern/js/user-siswa.js';

$sql = 'SELECT * FROM module LEFT JOIN module_status USING(id_module_status) ORDER BY nama_module';
$data['list_module'] = $db->query($sql)->getResultArray();

switch ($_GET['action']) 
{
	default: 
		action_notfound();
		
	// INDEX 
	case 'index':
		
		$data['message'] = [];
		
		if (!empty($_POST['submit'])) 
		{
			cek_hakakses('update_data');
			
			$form_errors = validate_form();
			$error = false;
						
			if ($form_errors) {
				$data['message']['content'] = $form_errors;
				$error = true;
			} else {
				$db->beginTrans();
				
				$sql = 'DELETE FROM setting WHERE type="user_siswa"';
				$db->query($sql);
				
				$param_value = ['login_enable', 'login_column', 'id_role'];
				foreach ($param_value as $value) {
					$data_db[] = ['type' => 'user_siswa', 'param' => $value, 'value' => $_POST[$value]];
				}
				
				$db->insertBatch('setting', $data_db);
								
				if ($_POST['reset_password_options'] == 'Y') {
					$password = password_hash(trim($_POST['reset_password_input']), PASSWORD_DEFAULT);
					$sql = 'UPDATE siswa SET password = "' . $password . '"';
					$db->query($sql);
				}
				
				$result = $db->completeTrans();
				
				if ($result) {
					$data['message']['status'] = 'ok';
					$data['message']['content'] = 'Data berhasil disimpan';
				} else {
					$data['message']['content'] = 'Data gagal disimpan';
					$error = true;
				}
				
			}
			
			if ($error) {
				$data['message']['status'] = 'error';
			}
		}
		
		$sql = 'SELECT * FROM setting WHERE type="user_siswa"';
		$query = $db->query($sql)->getResultArray();
		foreach($query as $val) {
			$data['setting'][$val['param']] = $val['value'];
		}
		
		$sql = 'SELECT * FROM role';
		$role = $db->query($sql)->result();
		$data['role'] = $role;
		
		$fields = $db->getField('siswa');
		foreach ($fields as $val) {			
			$data['fields'][$val['column_name']] = $val['column_name'];
		}
		
		$data['title'] = $current_module['judul_module'];
		load_view('views/form.php', $data);
}

function validate_form() 
{
	require_once('app/libraries/FormValidation.php');
	$validation = new FormValidation();
	$validation->setRules('login_enable', 'Perbolehkan Login', 'trim|required');
	$validation->setRules('login_column', 'Kolom Login', 'trim|required');
	
	$validation->validate();
	$form_errors =  $validation->getMessage();
		
	return $form_errors;
}