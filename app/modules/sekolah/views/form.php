<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<?php 
			include 'app/helpers/html_helper.php';
			echo btn_label(['attr' => ['class' => 'btn btn-success btn-xs'],
				'url' => module_url() . '?action=edit',
				'icon' => 'fa fa-plus',
				'label' => 'Tambah Data'
			]);
			
			echo btn_label(['attr' => ['class' => 'btn btn-light btn-xs'],
				'url' => module_url(),
				'icon' => 'fa fa-arrow-circle-left',
				'label' => 'Data Sekolah'
			]);
		?>
		<hr/>
		<?php
		
		if (!empty($msg)) {
			show_message($msg['content'], $msg['status']);
		}
		?>
		<form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
			<div class="tab-content" id="myTabContent">
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Dinas</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="nama_dinas" value="<?=set_value('nama_dinas', @$nama_dinas)?>" required="required"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Sekolah</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="nama_sekolah" value="<?=set_value('nama_sekolah', @$nama_sekolah)?>" required="required"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Alamat</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="alamat" value="<?=set_value('alamat', @$alamat)?>"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tlp/Fax</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="tlp_fax" value="<?=set_value('tlp_fax', @$tlp_fax)?>"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Website</label>
					<div class="col-sm-5">
						<input class="form-control" type="text" name="website" value="<?=set_value('website', @$website)?>" required="required"/>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Logo</label>
					<div class="col-sm-5">
						<?php 
						$file_logo = @$logo;
						if (!empty($file_logo))
						echo '<div class="list-foto" style="margin:inherit;margin-bottom:10px"><img src="'.BASE_URL. $config['kartu_path'] . $file_logo . '"/></div>';
						
						?>
						<input type="file" class="file" name="logo">
							<?php if (!empty($form_errors['logo'])) echo '<small class="alert alert-danger">' . $form_errors['logo'] . '</small>'?>
							<input type="hidden" name="max_image_size" value="307200"/>
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