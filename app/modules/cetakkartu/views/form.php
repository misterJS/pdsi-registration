<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<?php 
			helper ('html');
			echo btn_label(['class' => 'btn btn-success btn-xs',
				'url' => module_url() . '?action=add',
				'icon' => 'fa fa-plus',
				'label' => 'Tambah Data'
			]);
			
			echo btn_label(['class' => 'btn btn-light btn-xs',
				'url' => module_url(),
				'icon' => 'fa fa-arrow-circle-left',
				'label' => $current_module['judul_module']
			]);
		?>
		<hr/>
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
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Siswa</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="nama" value="<?=set_value('nama', @$nama)?>" required="required"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tempat Lahir</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="tempat_lahir" value="<?=set_value('tempat_lahir', @$tempat_lahir)?>" required="required"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tgl. Lahir</label>
					<div class="col-sm-5">
						<input class="form-control date-picker" type="text" name="tgl_lahir" value="<?=set_value('tgl_lahir', @$tgl_lahir)?>"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">NPM</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="npm" value="<?=set_value('npm', @$npm)?>"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Prodi</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="prodi" value="<?=set_value('prodi', @$prodi)?>" required="required"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Fakultas</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="fakultas" value="<?=set_value('fakultas', @$fakultas)?>" required="required"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Alamat</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="alamat" value="<?=set_value('alamat', @$alamat)?>" required="required"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Foto (Image Upload)</label>
					<div class="col-sm-5">
						<?php 
						if (!empty($foto) && file_exists(BASEPATH . 'public/images/foto/' . $foto))
							echo '<div class="list-foto mb-2" style="margin:inherit"><img src="'.BASE_URL. $config['foto_path'] . $foto . '"/></div>';
						
						?>
						<input type="file" class="file" name="foto">
							<?php if (!empty($form_errors['foto'])) echo '<small class="alert alert-danger">' . $form_errors['foto'] . '</small>'?>
							<small class="small" style="display:block">Maksimal 300Kb, Minimal 100px x 100px, Tipe file: .JPG, .JPEG, .PNG</small>
						<div class="upload-img-thumb"><span class="img-prop"></span></div>
					</div>
				</div>
				<div class="form-group row mb-0">
					<div class="col-sm-5">
						<button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
						<input type="hidden" name="id" value="<?=@$_GET['id']?>"/>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>