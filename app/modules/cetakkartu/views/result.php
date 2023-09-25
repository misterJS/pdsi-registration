<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$current_module['judul_module']?></h5>
	</div>
	
	<div class="card-body">
		<?php 
		if (!$result) {
			show_message('Data tidak ditemukan', 'error', false);
		} else {
			if (!empty($msg)) {
				show_alert($msg);
			}
			helper('html');
			echo '<form id="form-cetak" method="get" action="" target="_blank">
					<input type="hidden" name="action" value="print"/>';

			echo btn_submit([
				'print' => ['url' => '?action=print'
								, 'btn_class' => 'btn-primary disabled btn-print-all'
								, 'icon' => 'fa fa-print'
								, 'text' => '&nbsp;Cetak Yang Dicentang'
								, 'attr' => ['target' => '_blank', 'disabled' => 'disabled']
							]
			]);

			echo '<hr/>';
			
			$column =[
						'ignore_search_checkall' => '<div class="form-check"><input type="checkbox" class="form-check-input checkall" name="checkall" value="checkall"/></div>'
						, 'id_siswa' => 'ID'
						, 'foto' => 'Foto'
						, 'nama' => 'Nama'
						, 'tgl_lahir' => 'TTL'
						, 'alamat' => 'Alamat'
						, 'nis' => 'NIS'
						, 'nisn' => 'NISN'
						, 'ignore_search_action' => 'Action'
					];
			$setting = ['order' => [3,'desc']];
			$th = '';
			foreach ($column as $val) {
				$th .= '<th>' . $val . '</th>'; 
			}
			?>
			
			<table id="table-result" class="table display table-striped table-bordered table-hover" style="width:100%">
			<thead>
				<tr>
					<?=$th?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<?=$th?>
				</tr>
			</tfoot>
			</table>
			<?php
				foreach ($column as $key => $val) {
					$column_dt[] = ['data' => $key];
				}
			?>
			<span id="dataTables-column" style="display:none"><?=json_encode($column_dt)?></span>
			<span id="dataTables-setting" style="display:none"><?=json_encode($setting)?></span>
			<span id="dataTables-url" style="display:none"><?=current_url() . '?action=getDataDT'?></span>
			<?php 
			echo'<hr/>';
			echo btn_submit([
				'print' => ['url' => '?action=print'
								, 'btn_class' => 'btn-primary disabled btn-print-all'
								, 'icon' => 'fa fa-print'
								, 'text' => '&nbsp;Cetak Yang Dicentang'
								, 'attr' => ['target' => '_blank', 'disabled' => 'disabled']
							]
			]);
			echo '</form>';
		} ?>
	</div>
</div>