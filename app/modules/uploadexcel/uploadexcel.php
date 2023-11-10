<?php
/**
*	Aplikasi Kartu Professional
*	Website	: https://pdsionline.org
* 	Author	: Agus Prawoto Hadi
*	Year	: 2022
*/

use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;

$data['title'] = 'Upload Excel';
$data['tabel'] = ['siswa' => ['file_excel' => ['url' => BASE_URL . 'public/files/Format Data Siswa.xlsx'
													, 'title' => 'Download File Excel Format Data Siswa'
													, 'display' => 'Format Data Siswa.xlsx'
												  ]
								  , 'display' => 'Siswa'
								]
				];

$js[] = BASE_URL . 'public/themes/modern/js/uploadexcel.js';
$js[] = ['print' => true, 'script' => 'var tabel = ' . json_encode($data['tabel'])];
				
foreach ($data['tabel'] as $key => $val) {
	$data['tabel_options'][$key] = $val['display'];
}				

helper('format');		

switch ($_GET['action']) 
{
	default: 
		action_notfound();
		
	// INDEX 
	case 'index':
		
		if (isset($_POST['submit'])) 
		{
			$path = BASEPATH . 'public/tmp/';
						
			if (!$_FILES['file_excel']['name']) {
				$form_errors['file_excel'] = 'File excel belum dipilih';
			}
			
			$form_errors = validate_form();
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {
				
				$filename = upload_file($path, $_FILES['file_excel']);
				
				require_once 'app/libraries/vendors/openspout/src/Autoloader/autoload.php';
				$reader = ReaderEntityFactory::createReaderFromFile($path . $filename);
				$reader->open($path . $filename);

				foreach ($reader->getSheetIterator() as $sheet) 
				{
					$total_row = 0;
					foreach ($sheet->getRowIterator() as $num_row => $row) 
					{
						$cols = $row->toArray();
						
						if ($num_row == 1) {
							$field_table = $cols;
							$field_name = array_map('strtolower', $field_table);
							continue;
						}
						
						$data_value = [];
						foreach ($field_name as $num_col => $field) 
						{
							$val = null;
							if (key_exists($num_col, $cols) && $cols[$num_col] != '') {
								$val = $cols[$num_col];
							}
							
							if ($val instanceof \DateTime) {
								$val = $val->format('Y-m-d H:i:s');
							}
							
							$data_value[$field] = $val;
						}
						
						$data_db[] = $data_value;
						$total_row += 1;
						
						if ($num_row % 2000 == 0) {
							$query = $db->insertBatch($_POST['nama_tabel'], $data_db);
							$data_db = [];
						}
					}
					
					if ($data_db) {
						$query = $db->insertBatch($_POST['nama_tabel'], $data_db);
					}
				}
				
				$reader->close();
				delete_file ($path . $filename);
				
				if ($query) {
					$data['msg']['status'] = 'ok';
					$data['msg']['content'] = 'Data berhasil di masukkan ke dalam tabel <strong>' . $_POST['nama_tabel'] . '</strong> sebanyak ' . format_ribuan($total_row) . ' baris';
				}
				
			}
		}
				
		load_view('views/form.php', $data);
}

function validate_form() {
	
	$form_errors = [];
	if ($_FILES['file_excel']['name']) 
	{
		$file_type = $_FILES['file_excel']['type'];
		$allowed = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		
		if (!in_array($file_type, $allowed)) {
			$form_errors['file_excel'] = 'Tipe file harus ' . join(', ', $allowed);
		}
	}
	
	return $form_errors;
}