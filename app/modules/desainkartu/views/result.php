<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$current_module['judul_module']?></h5>
	</div>
	
	<div class="card-body">
		<a href="<?=current_url()?>/add" class="btn btn-success btn-xs"><i class="fa fa-plus pe-1"></i> Tambah Data</a>
		<hr/>
		<?php 

		$column =[
					'id_setting_kartu' => 'ID'
					, 'nama_setting' => 'Nama Setting'
					, 'background_depan' => 'Background Depan'
					, 'background_belakang' => 'Background Belakang'
					, 'gunakan' => 'Gunakan'
					, 'ignore_action' => 'Action'
				];
		$setting = ['order' => [1,'asc']];
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
	</div>
</div>