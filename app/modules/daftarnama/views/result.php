<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?= $current_module['judul_module'] ?></h5>
	</div>

	<div class="card-body">
		<?php
		global $list_action;
		if ($list_action['create_data'] == 'yes') {
			$results = true;

			foreach ($result as $data) {
				if ($data['email'] === $_SESSION['user']['email']) {
					$hasilFilter[] = $data;
				}
			}

			if (!empty($hasilFilter)) {
				$results = false;
			} else {
				$results = true;
			}

			if ($results) {
				echo '<a href="daftarnama/add" class="btn btn-success btn-xs"><i class="fa fa-plus pe-1"></i> Tambah Data</a>
				<hr/>';
			}
		}

		if (!$result) {
			show_message('Data tidak ditemukan', 'error', false);
		} else {
			if (!empty($msg)) {
				show_alert($msg);
			}

			$column = [
				'id_siswa' => 'ID', 'foto' => 'Foto', 'nama' => 'Nama', 'jenis_kelamin' => 'L/P', 'tgl_lahir' => 'TTL', 'alamat' => 'Alamat', 'province' => 'Provinsi', 'whatsapp' => 'Whatsapp', 'ignore_search_action' => 'Action'
			];
			$setting = ['order' => [2, 'asc']];
			$th = '';
			foreach ($column as $val) {
				$th .= '<th>' . $val . '</th>';
			}
		?>

			<table id="table-result" class="table display table-striped table-bordered table-hover" style="width:100%">
				<thead>
					<tr>
						<?= $th ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<?= $th ?>
					</tr>
				</tfoot>
			</table>
			<?php
			foreach ($column as $key => $val) {
				$column_dt[] = ['data' => $key];
			}
			?>
			<span id="dataTables-column" style="display:none"><?= json_encode($column_dt) ?></span>
			<span id="dataTables-setting" style="display:none"><?= json_encode($setting) ?></span>
			<span id="dataTables-url" style="display:none"><?= current_url() . '?action=getDataDT' ?></span>
		<?php
		} ?>
	</div>
</div>