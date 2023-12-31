<?php
/**
* PHP *	Year		: 2022
* Author	: Agus Prawoto Hadi
* Website	: https://pdsionline.org
* Year		: 2021-2022
*/

$js[] = BASE_URL . 'public/vendors/chartjs/chart.js';
$js[] = BASE_URL . 'public/themes/modern/js/chartjs.js';

switch ($_GET['action']) 
{
    default: 
        action_notfound();
    
    	// INDEX 
    case 'index':
	
		$sql= 'SELECT YEAR(tgl_transaksi) AS tahun
				FROM toko_penjualan
				GROUP BY tahun';
		$result = $db->query($sql)->getResultArray();
		
		$list_tahun = [];
		foreach ($result as $val) {
			$list_tahun[$val['tahun']] = $val['tahun'];
		}
		
		$tahun = '';
		if (empty($_GET['tahun'])) {
			$tahun = max($list_tahun);
		}
		
		if (!empty($_GET['tahun']) && in_array($_GET['tahun'], $list_tahun)) {
			$tahun = $_GET['tahun']; 
		}
			
        $sql = 'SELECT MONTH(tgl_transaksi) AS bulan, COUNT(id_penjualan) as JML, SUM(total_harga) total
				FROM toko_penjualan
				WHERE tgl_transaksi >= "' . $tahun . '-01-01" AND tgl_transaksi <= "' . $tahun . '-12-31"
				GROUP BY MONTH(tgl_transaksi)';
				
        $penjualan = $db->query($sql)->getResultArray();
		
		$sql = 'SELECT id_barang, nama_barang, COUNT(id_barang) AS jml
				FROM toko_penjualan_detail
				LEFT JOIN toko_penjualan USING(id_penjualan)
				LEFT JOIN toko_barang USING(id_barang)
				WHERE tgl_transaksi >= "' . $tahun . '-01-01" AND tgl_transaksi <= "' . $tahun . '-12-31"
				GROUP BY id_barang
				ORDER BY jml DESC LIMIT 7';
		
		$item_terjual = $db->query($sql)->getResultArray();
		
		$data['list_tahun'] = $list_tahun;
        $data['penjualan'] = $penjualan;
        $data['item_terjual'] = $item_terjual;
        $data['tahun'] = $tahun;

        $data['message']['status'] = 'ok';
        if (empty($data['penjualan'])) {
            $data['message']['status'] = 'error';
            $data['message']['message'] = 'Data tidak ditemukan';
		}

        load_view('views/result.php', $data);
}