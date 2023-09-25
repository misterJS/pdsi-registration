<?php helper('html')?>
<div class="card-body dashboard">
	<div class="row">
		<div class="col-lg-3 col-sm-6 col-xs-12 mb-4">
			<div class="card text-bg-primary shadow">
				<div class="card-body card-stats">
					<div class="description">
						<h5 class="card-title h4"><?=format_number($total_siswa)?></h5>
						<p class="card-text">Total Siswa</p>
						
					</div>
					<div class="icon bg-warning-light">
						<!-- <i class="fas fa-clipboard-list"></i> -->
						<i class="material-icons">groups</i>
					</div>
				</div>
				<div class="card-footer">
					<div class="card-footer-left">
						<div class="icon me-2">
							
						</div>
						<p><?=format_number($total_siswa) . '/' . format_number($total_siswa)?></p>
					</div>
					<div class="card-footer-right">
						<p>100%</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-6 col-xs-12 mb-4">
			<div class="card text-white bg-success shadow">
				<div class="card-body card-stats">
					<div class="description">
						<h5 class="card-title"><?=format_number($siswa_gender['jml_laki'])?></h5>
						<p class="card-text">Siswa Laki Laki</p>
					</div>
					<div class="icon">
						<!-- <i class="fas fa-shopping-cart"></i>-->
						<i class="material-icons">face</i>
					</div>
				</div>
				<div class="card-footer">
					<div class="card-footer-left">
						<div class="icon me-2">
							
						</div>
						<p><?=format_number($siswa_gender['jml_laki']) . '/' . format_number($total_siswa)?></p>
					</div>
					<div class="card-footer-right">
						<p><?=round( $siswa_gender['jml_laki']/$total_siswa*100 )?>%</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-6 col-xs-12 mb-4">
			<div class="card text-white bg-warning shadow">
				<div class="card-body card-stats">
					<div class="description">
						<h5 class="card-title"><?=format_number($siswa_gender['jml_perempuan'])?></h5>
						<p class="card-text">Siswa Perempuan</p>
					</div>
					<div class="icon">
						<!-- <i class="fas fa-money-bill-wave"></i> -->
						<i class="material-icons">face_3</i>
					</div>
				</div>
				<div class="card-footer">
					<div class="card-footer-left">
						<div class="icon me-2">
							
						</div>
						<?=format_number($siswa_gender['jml_perempuan']) . '/' . format_number($total_siswa)?>
					</div>
					<div class="card-footer-right">
						<p><?=round( $siswa_gender['jml_perempuan']/$total_siswa*100 )?>%</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-6 col-xs-12 mb-4">
			<a href="<?=BASE_URL . 'cetakkartu'?>" title="Cetak Kartu" class="btn btn-depan btn-light p-2 p-0 bg-opacity-10 w-100 align-items-stretch d-flex justify-content-between shadow-sm" style="padding:0 !important; height: 138px;">
				<div class="text text-start py-3 px-3">
					<h5 class="title">Cetak Kartu</h5>
					<hr/>
					<small class="text-muted">Cetak kartu, ekspor PDF, dan kirim email</small>
				</div>
				<div class="icon d-flex bg-danger bg-opacity-5 align-items-center justify-content-center rounded-end" style="min-width: 75px">
					<i class="material-icons" style="font-size:40px">print</i>
				</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-9 col-xl-9 mb-4">
			<div class="card">
				<div class="card-header">
					<div class="card-header-start">
						<h6 class="card-title">Daftar Siswa</h6>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<?php
						$column =[
									'ignore_urut' => 'No'
									, 'nama' => 'Nama Siswa'
									, 'alamat' => 'Alamat'
									, 'nis' => 'NIS'
									, 'nisn' => 'NISN'
									, 'ignore_action' => 'Action'
								];
						
						$settings['order'] = [1,'asc'];
						$index = 0;
						$th = '';
						foreach ($column as $key => $val) {
							$th .= '<th>' . $val . '</th>'; 
							if (strpos($key, 'ignore_search') !== false) {
								$settings['columnDefs'][] = ["targets" => $index, "orderable" => false];
							}
							$index++;
						}
						
						?>
						
						<table id="tabel-list-siswa" class="table display table-striped table-hover" style="width:100%">
						<thead>
							<tr>
								<?=$th?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="<?=count($column)?>" class="text-center">Loading data...</td>
							</tr>
						</tbody>
						</table>
						<?php
							foreach ($column as $key => $val) {
								$column_dt[] = ['data' => $key];
							}
						?>
						<span id="list-siswa-column" style="display:none"><?=json_encode($column_dt)?></span>
						<span id="list-siswa-setting" style="display:none"><?=json_encode($settings)?></span>
						<span id="list-siswa-url" style="display:none"><?=$config['base_url'] . 'dashboard/getDataDTListSiswa'?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-lg-3 col-xl-3 mb-4">
			<div class="card" style="height:calc(100% - 30px)">
				<div class="card-header">
					<div class="card-header-start">
						<h5 class="card-title">Jenis Siswa</h5>
					</div>
				</div>
				<div class="card-body" style="display:flex">
					<canvas id="chart-jenis-siswa" style="margin:auto;max-width:350px;width:100%"></canvas>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-4 col-sm-6 col-xs-12">
			<div class="card">
				<div class="card-header">Import Export</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-6 col-sm-6 col-xs-12 mb-4">
							<a href="<?=BASE_URL . 'uploadexcel'?>" title="Upload Excel" class="btn btn-depan btn-light p-2 p-0 bg-opacity-10 w-100 align-items-stretch d-flex justify-content-between shadow-sm" style="padding:0 !important">
								<div class="text text-start py-3 px-3">
									<h5 class="title">Upload</h5>
									<small class="text-muted">Excel</small>
								</div>
								<div class="icon-sm d-flex bg-success bg-opacity-5 align-items-center justify-content-center">
									<i class="material-icons">upload_file</i>
								</div>
							</a>
						</div>
						<div class="col-lg-6 col-sm-6 col-xs-12 mb-4">
							<a href="<?=BASE_URL . 'downloadexcel'?>" title="Download Excel" class="btn btn-depan btn-light p-2 p-0 bg-opacity-10 w-100 align-items-stretch d-flex justify-content-between shadow-sm" style="padding:0 !important">
								<div class="text text-start py-3 px-3">
									<h5 class="title">Download</h5>
									<small class="text-muted">Excel</small>
								</div>
								<div class="icon-sm d-flex bg-success bg-opacity-5 align-items-center justify-content-center">
									<i class="material-icons">file_download</i>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8 col-sm-6 col-xs-12">
			<div class="card">
				<div class="card-header">Quick Menu</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-4 col-sm-6 col-xs-12 mb-4">
							<a href="<?=BASE_URL . 'desainkartu'?>" title="Cetak Kartu" class="btn btn-depan btn-light p-2 p-0 bg-opacity-10 w-100 align-items-stretch d-flex justify-content-between shadow-sm" style="padding:0 !important">
								<div class="text text-start py-3 px-3">
									<h5 class="title">Desain Kartu</h5>
									<small class="text-muted">Ubah layout kartu</small>
								</div>
								<div class="icon d-flex bg-primary text-light bg-opacity-5 align-items-center justify-content-center">
									<i class="material-icons">color_lens</i>
								</div>
							</a>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-12 mb-4">
							<a href="<?=BASE_URL . 'settingprinter'?>" title="Setting Printer" class="btn btn-depan btn-light p-2 p-0 bg-opacity-10 w-100 align-items-stretch d-flex justify-content-between shadow-sm" style="padding:0 !important">
								<div class="text text-start py-3 px-3">
									<h5 class="title">Setting Printer</h5>
									<small class="text-muted">Ubah margin kertas</small>
								</div>
								<div class="icon d-flex bg-danger bg-opacity-5 align-items-center justify-content-center">
									<i class="material-icons">print</i>
								</div>
							</a>
						</div>
						<div class="col-lg-4 col-sm-6 col-xs-12 mb-4">
							<a href="<?=BASE_URL . 'desainkartu'?>" title="Setting QRCode" class="btn btn-depan btn-light p-2 p-0 bg-opacity-10 w-100 align-items-stretch d-flex justify-content-between shadow-sm" style="padding:0 !important">
								<div class="text text-start py-3 px-3">
									<h5 class="title">Setting QRCode</h5>
									<small class="text-muted">Ubah data QRCode</small>
								</div>
								<div class="icon d-flex bg-warning bg-opacity-5 align-items-center justify-content-center">
									<i class="material-icons">qr_code</i>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
	let siswa_gender = <?=json_encode(array_values($siswa_gender))?>;
</script>