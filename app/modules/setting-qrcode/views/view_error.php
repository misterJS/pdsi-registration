<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	<div class="card-body">
		<?php 
		
		include 'app/helpers/html_helper.php';
		if (!empty($message)) {
			show_message($message);
		}
		
		?>
	</div>
</div>