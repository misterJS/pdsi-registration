<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?= $title ?></h5>
	</div>

	<div class="card-body">
		<?php
		include 'app/helpers/html_helper.php';
		echo btn_label([
			'attr' => ['class' => 'btn btn-success btn-xs'],
			'url' => module_url() . '?action=add',
			'icon' => 'fa fa-plus',
			'label' => 'Tambah Data'
		]);

		echo btn_label([
			'attr' => ['class' => 'btn btn-light btn-xs'],
			'url' => module_url(),
			'icon' => 'fa fa-arrow-circle-left',
			'label' => 'Daftar Anggota'
		]);
		?>
		<hr />
		<?php
		if (@$tgl_lahir) {
			$exp = explode('-', $tgl_lahir);
			$tgl_lahir = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
		}
		if (!empty($msg)) {
			show_message($msg['content'], $msg['status']);
		}
		?>
		<form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
			<div class="tab-content" id="myTabContent">
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Foto</label>
					<div class="col-sm-5">
						<?php
						if (!empty($foto)) {
							if (!file_exists(BASE_PATH . $config['foto_path'] . $foto)) {
								$foto = 'noimage.png';
							}
							echo '<div class="foto-container mb-2" style="margin:inherit"><img src="' . BASE_URL . $config['foto_path'] . $foto . '"/></div>';
						}

						?>
						<input type="file" class="file form-control" name="foto">
						<?php if (!empty($form_errors['foto'])) echo '<small class="alert alert-danger">' . $form_errors['foto'] . '</small>' ?>
						<small class="small" style="display:block">Maksimal 1Mb, Minimal 100px x 100px, Tipe file: .JPG, .JPEG, .PNG</small>
						<div class="upload-img-thumb"><span class="img-prop"></span></div>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Anggota</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="nama" value="<?= set_value('nama', @$nama) ?>" required="required" />
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Jenis Kelamin</label>
					<div class="col-sm-5">
						<?= options(['name' => 'jenis_kelamin'], ['L' => 'Laki Laki', 'P' => 'Perempuan'], @$jenis_kelamin) ?>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Email</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="email" value="<?= set_value('email', @$email) ?>" required="required" />
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tempat Lahir</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="tempat_lahir" value="<?= set_value('tempat_lahir', @$tempat_lahir) ?>" required="required" />
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tgl. Lahir</label>
					<div class="col-sm-5">
						<input class="form-control date-picker" type="text" name="tgl_lahir" value="<?= set_value('tgl_lahir', @$tgl_lahir) ?>" />
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Telepon</label>
					<div class="col-sm-5">
						<input class="form-control" type="number" name="telepon" value="<?= set_value('telepon', @$telepon) ?>" />
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Whatsapp</label>
					<div class="col-sm-5">
						<input class="form-control" type="number" name="whatsapp" value="<?= set_value('whatsapp', @$whatsapp) ?>" />
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Alamat</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="alamat" value="<?= set_value('alamat', @$alamat) ?>" required="required" />
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Provinsi</label>
					<div class="col-sm-5">
						<select name="province" class="form-control">
							<?php
							$sql = 'SELECT * FROM wilayah_propinsi';
							$result = $db->query($sql)->getResultArray();
							foreach ($result as $row) {
								$selected = (@$provinsi == $row['id_wilayah_propinsi']) ? 'selected' : '';
								echo '<option value="' . $row['id_wilayah_propinsi'] . '" ' . $selected . '>' . $row['nama_propinsi'] . '</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group row mb-0">
					<div class="col-sm-5">
						<button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
						<input type="hidden" name="id" value="<?= @$_GET['id'] ?>" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>