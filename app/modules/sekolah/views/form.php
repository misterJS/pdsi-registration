<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?= $title ?></h5>
	</div>

	<div class="card-body">
		<?php
		include 'app/helpers/html_helper.php';
		echo btn_label([
			'attr' => ['class' => 'btn btn-success btn-xs'],
			'url' => module_url() . '?action=edit',
			'icon' => 'fa fa-plus',
			'label' => 'Tambah Data'
		]);

		echo btn_label([
			'attr' => ['class' => 'btn btn-light btn-xs'],
			'url' => module_url(),
			'icon' => 'fa fa-arrow-circle-left',
			'label' => 'Data Pendidikan'
		]);
		?>
		<hr />
		<?php

		if (!empty($msg)) {
			show_message($msg['content'], $msg['status']);
		}
		?>
		<form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
			<div class="tab-content" id="myTabContent">
				<div class="form-group mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Jenjang</label>
					<div class="col-sm-5">
						<select name="jenjang" class="form-control">
							<?php
							$sql = 'SELECT * FROM jenjang_pendidikan';
							$result = $db->query($sql)->getResultArray();
							foreach ($result as $row) {
								$selected = (@$jenjang == $row['id_jenjang']) ? 'selected' : '';
								echo '<option value="' . $row['id_jenjang'] . '" ' . $selected . '>' . $row['nama'] . '</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Universitas</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="nama_sekolah" value="<?= set_value('nama_sekolah', @$nama_sekolah) ?>" required="required" />
					</div>
				</div>
				<div class="form-group mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Jurusan</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="jurusan" value="<?= set_value('jurusan', @$jurusan) ?>" required="required" />
					</div>
				</div>
				<div class="form-group row mb-3 col-sm-8">
					<div class="col-sm-6">
						<label class="col-sm-12 col-form-label">Tahun Masuk</label>
						<div class="col-sm-12">
							<input class="form-control date-picker" type="text" name="tahun_masuk" value="<?= set_value('tahun_masuk', @$tahun_masuk) ?>" />
						</div>
					</div>
					<div class="col-sm-6">
						<label class="col-sm-12 col-form-label">Tahun Keluar</label>
						<div class="col-sm-12">
							<input class="form-control date-picker" type="text" name="tahun_keluar" value="<?= set_value('tahun_keluar', @$tahun_keluar) ?>" />
						</div>
					</div>
				</div>
				<div class="form-group mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tahun Lulus Profesi</label>
					<div class="col-sm-5">
						<input class="form-control date-picker" type="text" name="tahun_keluar_profesi" value="<?= set_value('tahun_keluar_profesi', @$tahun_keluar_profesi) ?>" />
					</div>
				</div>
				<div class="form-group mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Upload Ijazah</label>
					<div class="col-sm-5">
						<?php
						$file_ijazah = @$ijazah;
						if (!empty($file_ijazah))
							echo '<div class="list-foto" style="margin:inherit;margin-bottom:10px"><img src="' . BASE_URL . $config['kartu_path'] . $file_ijazah . '"/></div>';

						?>
						<input type="file" class="file" name="ijazah">
						<?php if (!empty($form_errors['ijazah'])) echo '<small class="alert alert-danger">' . $form_errors['ijazah'] . '</small>' ?>
						<input type="hidden" name="max_image_size" value="2007200" />
						<small class="small" style="display:block">Maksimal 2mb, Minimal 100px x 100px, Tipe file: .PDF, .PNG</small>
						<div class="upload-img-thumb"><span class="img-prop"></span></div>
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