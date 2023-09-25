<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<?php 
			helper ('html');
		if (!empty($message)) {
			show_message($message);
		}
		?>
		<form method="post" action="" id="form-setting" enctype="multipart/form-data">
			<div class="tab-content">
				<div class="row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Perbolehkan Login</label>
					<div class="col-sm-5">
						<?php 
						echo options(['name' => 'login_enable'], ['Y' => 'Ya', 'N' => 'Tidak'], $setting['login_enable']);
						?>
						<small>Perbolehkan siswa login ke aplikasi?</small>
					</div>
				</div>
				<?php
				$display = @$enable == 'N' ? ' style="display:none"' : '';
				?>
				<div class="detail-container"<?=$display?>>
					<div class="row mb-3">
						<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Kolom Data Login</label>
						<div class="col-sm-5">
							<?php 
							echo options(['name' => 'login_column'], $fields , $setting['login_column']);
							?>
							<small>Kolom tabel siswa yang digunakan untuk pengecekan login</small>
						</div>
					</div>
					<div class="row mb-3">
						<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Role User Siswa</label>
						<div class="col-sm-5">
							<?php
							foreach ($role as $key => $val) {
								$options[$val['id_role']] = $val['judul_role'];
							}
							echo options(['name' => 'id_role'], $options, set_value('id_role', $setting['id_role']));
							?>
							<small>Role untuk user siswa.</small>
						</div>
					</div>
					<div class="row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Reset Password</label>
					<div class="col-sm-5">
						<?php 
						echo options(['name' => 'reset_password_options', 'id' => 'reset-password-options'], ['Y' => 'Ya', 'N' => 'Tidak'], 'N');
						?>
						<input name="reset_password_input" placeholder="Password baru" class="form-control mt-2" id="reset-password-input" value="" style="display:none"/>
						<small>Reset semua password siswa</small>
					</div>
				</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<button type="submit" name="submit" id="btn-submit" value="submit" class="btn btn-primary">Submit</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>