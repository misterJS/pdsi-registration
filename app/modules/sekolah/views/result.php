<div class="card">
	<div class="card-header">
		<h5 class="card-title">Data Pendidikan</h5>
	</div>

	<div class="card-body">
		<a href="?action=edit" class="btn btn-success btn-xs"><i class="fa fa-plus pe-1"></i> Tambah Data</a>
		<hr />
		<?php
		if (!$result) {
			show_message('Data tidak ditemukan', 'error', false);
		} else {
			if (!empty($msg)) {
				show_alert($msg);
			}
		?>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>No</th>
							<th>Ijazah</th>
							<th>Universitas</th>
							<th>Nama Dinas</th>
							<th>Alamat</th>
							<th>Tlp/Fax</th>
							<th>Website</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						require_once('app/helpers/html_helper.php');

						$i = 1;
						foreach ($result as $key => $val) {
							echo '<tr>
						<td>' . $i . '</td>
						<td><div class="list-foto"><embed src="' . BASE_URL . $config['kartu_path'] . $val['ijazah'] . '" type="application/pdf" width="100%" height="100px" /></div></td>
						<td>' . $val['nama_sekolah'] . '</td>
						<td>' . $val['nama_dinas'] . '</td>
						<td>' . $val['alamat'] . '</td>
						<td>' . $val['tlp_fax'] . '</td>
						<td>' . $val['website'] . '</td>
						<td>' . btn_action([
								'edit' => ['url' => '?action=edit&id=' . $val['id_sekolah']], 'delete' => [
									'url' => '', 'id' =>  $val['id_sekolah'], 'delete-title' => 'Hapus data sekolah: <strong>' . $val['nama_sekolah'] . '</strong> ?'
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