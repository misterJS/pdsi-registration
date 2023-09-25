<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$current_module['judul_module']?></h5>
	</div>
	
	<div class="card-body">
		<a href="?action=add" class="btn btn-success btn-xs"><i class="fa fa-plus pe-1"></i> Tambah Data</a>
		<hr/>
		<?php 
		include 'app/helpers/html_helper.php';
		if (!$result) {
			show_message('Data tidak ditemukan', 'error', false);
		} else {
			if (!empty($message)) {
				show_alert($message);
			}
			?>
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
				<th>No</th>
				<th>DPI</th>
				<th>Margin Kiri</th>
				<th>Margin Atas</th>
				<th>Margin Kartu Kanan</th>
				<th>Margin Kartu Bawah</th>
				<th>Margin Kartu Depan Belakang</th>
				<th>Gunakan</th>
				<th>Aksi</th>
			</tr>
			</thead>
			<tbody>
			<?php
			require_once('app/helpers/html_helper.php');
			
			$i = 1;
			foreach ($result as $key => $val) {
				$checked = $val['gunakan'] == 1 ? ' checked="checked"' : '';
				echo '<tr>
						<td>' . $i . '</td>
						<td>' . $val['dpi'] . '</td>
						<td>' . $val['margin_left'] . '</td>
						<td>' . $val['margin_top'] . '</td>
						<td>' . $val['margin_kartu_right'] . '</td>
						<td>' . $val['margin_kartu_bottom'] . '</td>
						<td>' . $val['margin_kartu_depan_belakang'] . '</td>
						<td>
							<div class="form-switch">
								<input name="gunakan" type="checkbox" class="form-check-input switch switch-gunakan" data-id="'.$val['id_setting_printer'].'" ' . $checked . '>
							</div>
						</td>
						<td>'. btn_action([
									'edit' => ['class' => 'mb-2', 'url' => '?action=edit&id='. $val['id_setting_printer']]
									, 'delete' => ['class' => 'mb-2', 'url' => ''
												, 'id' =>  $val['id_setting_printer']
												, 'delete-title' => 'Hapus data ?'
											]
							]) .
						'</td>
					</tr>';
					$i++;
			}
			?>
			</tbody>
			</table>
			</div>
			<?php 
		} ?>
	</div>
</div>