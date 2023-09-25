<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<a href="?action=add" class="btn btn-success btn-xs me-2"><i class="fa fa-plus me-1"></i> Tambah Data</a>
		<a href="<?=$config['base_url'] . 'settingprinter'?>" class="btn btn-light btn-xs"><i class="fa fa-arrow-circle-left me-2"></i> Daftar Setting</a>
		<hr/>
		<?php 
			include 'app/helpers/html_helper.php';
		if (!empty($message)) {
			show_message($message);
		}
		if (empty($dpi)) {
			$dpi = 100;
		}
		$list_param = ['margin_left', 'margin_top', 'margin_kartu_right', 'margin_kartu_bottom', 'margin_kartu_depan_belakang'];
		foreach ($list_param as $val_param) {
			if ( empty(${$val_param}) ) {
				${$val_param} = 10;
			}
		}
	
		?>
		<form method="post" action="" class="form-horizontal">
			<div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Dot Per Inchi</label>
					<div class="col-sm-5">
						<input type="range" value="<?=set_value('dpi', $dpi)?>" class="form-range me-3" step="0.1" min="100" max="300">
						<div class="input-group">
							<input type="number" step="0.1" min="100" max="300" class=" form-control" name="dpi" value="<?=$dpi?>" />
							<span class="input-group-text">dpi</span>
						</div>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Margin Kiri</label>
					<div class="col-sm-5">
						<input type="range" value="<?=set_value('margin_left', $margin_left)?>" class="form-range me-3" step="0.1" min="0" max="20">
						<div class="input-group">
							<input type="number" step="0.1" min="0" max="20" class=" form-control" name="margin_left" value="<?=$margin_left?>" />
							<span class="input-group-text">mm</span>
						</div>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Margin Atas</label>
					<div class="col-sm-5">
						<input type="range" value="<?=set_value('margin_top', $margin_top)?>" class="form-range me-3" step="0.1" min="0" max="20">
						<div class="input-group">
							<input type="number" step="0.1" min="0" max="20" class=" form-control" name="margin_top" value="<?=$margin_top?>" />
							<span class="input-group-text">mm</span>
						</div>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Margin Kartu Kanan</label>
					<div class="col-sm-5">
						<input type="range" value="<?=set_value('margin_kartu_right', $margin_kartu_right)?>" class="form-range me-3" step="0.1" min="0" max="20">
						<div class="input-group">
							<input type="number" step="0.1" min="0" max="20" class=" form-control" name="margin_kartu_right" value="<?=$margin_kartu_right?>" />
							<span class="input-group-text">mm</span>
						</div>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Margin Kartu Bawah</label>
					<div class="col-sm-5">
						<input type="range" value="<?=set_value('margin_kartu_bottom', $margin_kartu_bottom)?>" class="form-range me-3" step="0.1" min="0" max="20">
						<div class="input-group">
							<input type="number" step="0.1" min="0" max="20" class=" form-control" name="margin_kartu_bottom" value="<?=$margin_kartu_bottom?>" />
							<span class="input-group-text">mm</span>
						</div>
					</div>
				</div>
				<div class="form-group row mb-3">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Margin Kartu Depan Belakang</label>
					<div class="col-sm-5">
						<input type="range" value="<?=set_value('margin_kartu_depan_belakang', $margin_kartu_depan_belakang)?>" class="form-range me-3" step="0.1" min="0" max="20">
						<div class="input-group">
							<input type="number" step="0.1" min="0" max="20" class=" form-control" name="margin_kartu_depan_belakang" value="<?=$margin_kartu_depan_belakang?>" />
							<span class="input-group-text">mm</span>
						</div>
						<small>Margin antara kartu depan dan belakang, kartu depan dan belakang dicetak atas bawah</small>
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