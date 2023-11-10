<?php
/**
*	Aplikasi Cetak Kartu
*	Developed by: Agus Prawoto Hadi
*	Website		: www.pdsionline.org
*	Year		: 2021
*/

$js[] = BASE_URL . 'public/vendors/datatables/dist/js/jquery.dataTables.min.js';
$js[] = BASE_URL . 'public/vendors/datatables/dist/js/dataTables.bootstrap5.min.js';
$styles[] = BASE_URL . 'public/vendors/datatables/dist/css/dataTables.bootstrap5.min.css';
// $js[] = BASE_URL . 'public/themes/modern/js/data-tables-ajax.js';
$js[] = BASE_URL . 'public/themes/modern/js/cetakkartu.js';

$site_title = 'Cetak Kartu';
$inchi = 25.4;
function showTtd($setting_kartu, $printer) {

	global $config, $inchi;

	$ttd_text = str_replace('{{YEAR}}', date('Y'), $setting_kartu['ttd_text']);
	$exp = explode("\r\n", $ttd_text);
	
	$content = '';
	$space = false;
	foreach ($exp as $val) {
		$val = trim($val);
		if ($val){
			$content .= '<p>' . $val . '</p>';
		} else {
			if (!$space) {
				$content .= '<div id="ttd-text-space-sign" style="height:' . ($setting_kartu['ttd_text_space_sign'] * $printer['dpi'] / 25.4) . 'px;"></div>';
				$space = true;
			}
		}
	}
	
	$kartu_height = $setting_kartu['kartu_height'] * $printer['dpi'] / $inchi;
	$html = '
	<style>
		
		
		.kartu-tandatangan {
			margin-top: '. ( ($kartu_height - ($setting_kartu['ttd_text_margin_top'] * $printer['dpi'] / $inchi))) .'px;
			margin-left: '. ($setting_kartu['ttd_text_margin_left'] * $printer['dpi'] / $inchi) .'px;
			text-align: '. ($setting_kartu['ttd_text_align']) .';
			font-size: '. ($setting_kartu['ttd_text_font_size']) .'px;
			font-family: '. ($setting_kartu['ttd_text_font_family']) .';
			font-weight: normal;
		}
		.kartu-tandatangan p{
			margin:0;
			padding:0;
			line-height: normal;
			white-space: nowrap;
		}

		.kartu-tandatangan-sign {
		
			width: '. ($setting_kartu['ttd_sign_width'] * $printer['dpi'] / $inchi) .'px;
			height: '. ($setting_kartu['ttd_sign_height'] * $printer['dpi'] / $inchi) .'px;
			margin-top: '. ($setting_kartu['ttd_sign_margin_top'] * $printer['dpi'] / $inchi) .'px;
			margin-left: '. ($setting_kartu['ttd_sign_margin_left'] * $printer['dpi'] / $inchi) .'px;
		}
		
		.kartu-tandatangan-sign img {
			max-width: '. ($setting_kartu['ttd_sign_width'] * $printer['dpi'] / $inchi) .'
			max-height: '. ($setting_kartu['ttd_sign_height'] * $printer['dpi'] / $inchi) .'
		}

		.kartu-tandatangan-cap {
		
			width: '. ($setting_kartu['ttd_cap_width'] * $printer['dpi'] / $inchi) .'px;
			height: '. ($setting_kartu['ttd_cap_height'] * $printer['dpi'] / $inchi) .'px;
			margin-top: '. ($setting_kartu['ttd_cap_margin_top'] * $printer['dpi'] / $inchi) .'px;
			margin-left: '. ($setting_kartu['ttd_cap_margin_left'] * $printer['dpi'] / $inchi) .'px;
			z-index: 5;
		}
		.kartu-tandatangan-cap img{
			max-width: '. ($setting_kartu['ttd_cap_width'] * $printer['dpi'] / $inchi) .'
			max-height: '. ($setting_kartu['ttd_cap_height'] * $printer['dpi'] / $inchi) .'
		}
				
	</style>
		
	<div id="ttd-container">
		<div class="kartu-tandatangan">' . $content . '</div>
		<div class="kartu-tandatangan-sign">
			<img id="ttd-sign-image" src="' . $config['base_url'] . 'public/images/kartu/' . $setting_kartu['ttd_sign_image'] . '"/>
		</div>
		<div class="kartu-tandatangan-cap">
			<img id="ttd-cap-image" src="' . $config['base_url'] . 'public/images/kartu/' . $setting_kartu['ttd_cap_image'] . '"/>
		</div>
	</div>';
	
	return $html;
}

function berlakuKartu($setting_kartu, $show = true) {
	$display = $show ? '' : ' style="display:none"';
	$html = '<div class="berlaku-container"' . $display . '>';
	if ($setting_kartu['berlaku_jenis'] == 'periode') {
		$exp = explode('-', $setting_kartu['berlaku_hingga_tanggal']);
		$html .= $setting_kartu['berlaku_periode_prefix'] . ' ' . $exp[2] . ' ' . nama_bulan( (int) $exp[1]) . ' ' . $exp[0];
	} else {
		$html .= $setting_kartu['berlaku_custom_text'];
	}
	$html .= '</div>';
	return $html;
}

function showQrcode($setting_kartu, $qrcode, $printer, $siswa, $show = true) {
	$display = $show ? '' : 'display:none;';
	$qrcode_margin_left = $setting_kartu['qrcode_margin_left'] * $printer['dpi'] / 25.4; 
	$qrcode_margin_top = $setting_kartu['qrcode_margin_top'] * $printer['dpi'] / 25.4; 
	$qrcode['content'] = '<div class="qrcode-container" style="position:absolute;' . $display . 'z-index:6;top:'.$qrcode_margin_top.'px;left:'. $qrcode_margin_left .'px;padding:' . $qrcode['padding'] . ';background:#FFFFFF">{{CONTENT}}</div>';
	
	if ($siswa['qrcode_text']) {
		$qrcode_text = $siswa['qrcode_text'];
	} else {
		if ($qrcode['content_jenis'] == 'field_database') {
			$qrcode_text = $siswa[$qrcode['content_field_database']] ;
			
		} else {
			$qrcode_text = $qrcode['content_global_text'];
		}
	}	
	
	$qrcode_content = generateQRCode($qrcode['version'], $qrcode['ecc'], $qrcode_text, $qrcode['size_module']);
	$qrcode['content'] = str_replace('{{CONTENT}}', $qrcode_content, $qrcode['content']);
	
	return $qrcode['content'];
}

switch ($_GET['action']) 
{
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
	
	case 'pdf':
	
		$data = set_data();

		$sql = 'SELECT * FROM siswa WHERE id_siswa = ?';
		$result = $db->query($sql, $_GET['id'])->getRowArray();
		$data['nama'] = $result;
		
		$sql = 'SELECT * FROM siswa_data_digunakan WHERE id_setting_kartu = ? ORDER BY urut';
		$result = $db->query($sql, 1)->getResultArray();
		$data['siswa_data_digunakan'] = $result;
		
		$data['fields'] = $db->getField('siswa');
		
		extract($data);

		require_once('app/libraries/vendors/tcpdf/tcpdf.php');
		
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
		$pdf->setPageUnit('mm');

		// set document information
		$pdf->SetCreator('Jagowebdev');
		$pdf->SetAuthor('Agus Prawoto Hadi');
		$pdf->SetTitle('Kartu Elektronik');
		$pdf->SetSubject('Kartu Pelajar');
		
		$margin_left = 10; //mm
		$margin_right = 10; //mm
		$margin_top = 15; //mm
		$font_size = 10;
		
		$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		
		$pdf->SetProtection(array('modify', 'copy', 'annot-forms', 'fill-forms', 'extract', 'assemble', 'print-high'), '', null, 0, null);

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		
		$pdf->SetFont('dejavusans', '', $font_size + 4, '', true);
		$pdf->SetMargins($margin_left, $margin_top, $margin_right, false);
		
		$pdf->AddPage();

		$pdf->StartTransform();
		$pdf->SetXY(0, 0);
		
		$pdf->SetTextColor(50,50,50);
		// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

		$pdf->Image(BASE_PATH . 'public/images/kartu/' . $setting_kartu['background_depan'], $printer['margin_left'], $printer['margin_top'], $setting_kartu['kartu_width'], $setting_kartu['kartu_height'], '', 'http://www.tcpdf.org', '', false);
		
		$pdf->SetFont ('helvetica', '', 8, '', '', true );
		$tbl = '<table border="0" cellspacing="0" cellpadding="0" style="width:250px">';
			
			foreach ($siswa_data_digunakan as $val_digunakan) {

				foreach ($fields as $name => $value) {
					
					if(strpos ($val_digunakan['pattern'], $name) !== false) {
						$data_value = $nama[$name];
						if ( strpos($name, 'tgl') !== false) {
							$exp = explode('-', $data_value);
							$data_value = $exp[2] . ' ' . nama_bulan( $exp[1] * 1 ) . ' ' . $exp[0];
						}
						
						$val_digunakan['pattern'] = str_replace($name, $data_value, $val_digunakan['pattern']);
					}
				}
				
				if ($setting_kartu['data_depan_show_label'] == 'Y') {
					
					$tbl .= <<<EOD
								<tr>
									<td style="width:50px">$val_digunakan[judul_data]</td>
									<td style="width:10px">:</td>
									<td>$val_digunakan[pattern]</td>
								</tr>

					EOD;
				} else {
					$tbl .= <<<EOD
								<tr>
									<td>$val_digunakan[pattern]</td>
								</tr>

					EOD;
				}
			}

		$tbl .= '</table>';
		$pdf->writeHTML($tbl, false, false, false, false, '');

		$pdf->Output('Kartu Elektronik.pdf', 'D');
		exit();
	
	case 'print':
		
			$data = set_data();
			
			$sql = 'SELECT * FROM siswa_data_digunakan WHERE id_setting_kartu = "' . $data['setting_kartu']['id_setting_kartu'] . '" ORDER BY urut';
			$result = $db->query($sql)->getResultArray();
			$data['siswa_data_digunakan']	= $result;
				
			$data['id'] = $_GET['id'];
			foreach ($data['id'] as $key => $val) {
				$sql = 'SELECT * FROM siswa WHERE id_siswa = ?';
				$result = $db->query($sql, trim($val))->result();
				$data['nama'][$val]	= $result[0];
			}
			
			$view = load_view('views/cetak.php', $data, true);
			echo $view;
		die;

	case 'getDataDT':

		$result['draw'] = $start = $_POST['draw'] ?: 1;
		
		$data_table = getListData();
		$result['recordsTotal'] = $data_table['total_data'];
		$result['recordsFiltered'] = $data_table['total_filtered'];
				
		helper('html');
		$id_user = $_SESSION['user']['id_user'];
		
		foreach ($data_table['content'] as $key => &$val) 
		{
			$foto = 'Anonymous.png';
			if ($val['foto']) {
				if (file_exists('public/images/foto/' . $val['foto'])) {
					$foto = $val['foto'];
				} else {
					$foto = 'noimage.png';
				}
			}
			
			$val['foto'] = '<div class="list-foto"><img src="'. BASE_URL.'public/images/foto/' . $foto . '"/></div>';
			
			$val['tgl_lahir'] = $val['tempat_lahir'] . ', '. format_tanggal($val['tgl_lahir']);
			$val['ignore_search_checkall'] = '<div class="form-check"><input type="checkbox" class="form-check-input checkbox" name="id[]" value="' . $val['id_siswa'] . '">';
			
			$val['ignore_search_action'] = 
							'<div class="btn-group">
								<a class="btn d-flex btn-xs align-items-center btn-danger" href="' . $config['base_url'] . 'cetakkartu/pdf?id[]='. $val['id_siswa'] . '"><i class="fas fa-file-pdf me-1"></i>PDF</a>
								<a class="btn d-flex btn-xs align-items-center btn-success" href="' . $config['base_url'] . 'cetakkartu/print?id[]='. $val['id_siswa'] . '"><i class="fas fa-print me-1"></i>Cetak</a>
								<button type="button" class="btn d-flex btn-xs align-items-center btn-primary" data-id="' . $val['id_siswa'] . '" data-email="' . $val['email'] . '" ><i class="fas fa-paper-plane me-1"></i>Email</button>
							</div>';
		}
					
		$result['data'] = $data_table['content'];
		echo json_encode($result); exit();
}

function getListData() {
	
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
	
	if (@$order[0]['column'] != '' ) {
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
	
	return ['total_data' => $total_data, 'total_filtered' => $total_filtered, 'content' => $content];
}

function set_data() {
	global $setting_web, $current_module, $db;

	$data['setting_web'] = $setting_web;
	$data['app_module'] = $current_module;

	$sql = 'SELECT * FROM setting_kartu WHERE gunakan = "Y"';
	$result = $db->query($sql)->getRowArray();
	$data['setting_kartu']	= $result;

	$sql = 'SELECT * FROM setting_qrcode';
	$result = $db->query($sql)->row();
	$data['qrcode']	= $result;
	
	$sql = 'SELECT * FROM setting_printer WHERE gunakan = 1';
	$result = $db->query($sql)->row();
	$data['printer']	= $result;
	
	$data['fields'] = $db->getField('siswa');
	
	return $data;
}