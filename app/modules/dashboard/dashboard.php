<?php
/**
* PHP *	Year		: 2022
* Author	: Agus Prawoto Hadi
* Website	: https://pdsionline.org
* Year		: 2021-2022
*/

$js[] = BASE_URL . 'public/vendors/chartjs/chart.js';
$styles[] = BASE_URL . 'public/vendors/material-icons/css.css';

$styles[] = BASE_URL . 'public/themes/modern/css/dashboard.css';
$js[] = BASE_URL . 'public/themes/modern/js/dashboard.js';
$js[] = BASE_URL . 'public/themes/modern/js/cetakkartu.js';

helper('format');

switch ($_GET['action']) 
{
    default: 
        action_notfound();
	
	case 'getDataDTListSiswa':

		$result['draw'] = $start = $_POST['draw'] ?: 1;
		
		$data_table = getListData();
		$result['recordsTotal'] = $data_table['total_data'];
		$result['recordsFiltered'] = $data_table['total_filtered'];
				
		helper('html');
		$id_user = $_SESSION['user']['id_user'];
		
		$no = $_POST['start'] + 1 ?: 1;
		
		foreach ($data_table['data'] as $key => &$val) 
		{
			$val['ignore_urut'] = $no;
			$val['ignore_action'] = '<div class="btn-group" style="color:#616161">
					<a href="' . BASE_URL . 'cetakkartu/print?id[]=' . $val['id_siswa'] . '" title="Cetak Kartu" class="btn btn-xs btn-light bg-success bg-opacity-5 text-light"><i class="fas fa-print"></i></a>
					<a href="' . BASE_URL . 'cetakkartu/pdf?id[]=' . $val['id_siswa'] . '" title="Download Kartu" class="btn btn-xs btn-light bg-danger bg-opacity-5 text-light"><i class="fas fa-file-pdf"></i></a>
					<button type="button" data-id="' . $val['id_siswa'] . '" data-email="' . $val['email'] . '" class="btn btn-xs btn-light bg-primary bg-opacity-5 text-light kirim-email"><i class="fas fa-paper-plane"></i></button>
				</div>';
			$no++;
		}
					
		$result['data'] = $data_table['data'];
		echo json_encode($result); 
		exit();
	
    	// INDEX 
    case 'index':
		
		helper('format');
		
		// Siswa
		$sql = 'SELECT COUNT(*) AS jml FROM siswa';
		$result =  $db->query($sql)->getRowArray();
		$data['total_siswa'] = $result['jml'];
		
		// Siswa Gender
		$sql = 'SELECT COUNT(IF(jenis_kelamin = "L", id_siswa, null)) AS jml_laki,
					COUNT(IF(jenis_kelamin = "P", id_siswa, null)) AS jml_perempuan
				FROM siswa';
		$result =  $db->query($sql)->getRowArray();
		$data['siswa_gender'] = $result;

		load_view('views/result.php', $data);
}

function getListData() {
	
	global $db;
	$columns = $_POST['columns'];
	$order_by = '';
	
	// Search
	$search_all = @$_POST['search']['value'];
	$where = ' WHERE 1=1 ';
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
	$order_data = $_POST['order'];
	$order = '';
	if (strpos($_POST['columns'][$order_data[0]['column']]['data'], 'ignore_search') === false) {
		$order_by = $columns[$order_data[0]['column']]['data'] . ' ' . strtoupper($order_data[0]['dir']);
		$order = ' ORDER BY ' . $order_by;
	}

	// Query Total
	$sql = 'SELECT COUNT(*) as jml_data
				FROM siswa';
				
	$query = $db->query($sql)->getRowArray();
	$total_data = $query['jml_data'];
	
	// Query Filtered
	$sql = '
			SELECT COUNT(*) AS jml_data 
			FROM siswa
			' . $where;
	$total_filtered = $db->query($sql)->getRowArray()['jml_data'];
	
	// Query Data
	$start = $_POST['start'] ?: 0;
	$length = $_POST['length'] ?: 10;
	$sql = '
			SELECT * FROM siswa
			' . $where . $order . ' LIMIT ' . $start . ', ' . $length;

	$data = $db->query($sql)->getResultArray();
	
	return ['total_data' => $total_data, 'total_filtered' => $total_filtered, 'data' => $data];
}