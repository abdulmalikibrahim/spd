<div class="row">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-body">
				<?php
				if(!empty($this->session->flashdata("notif"))){
					echo $this->session->flashdata("notif");
				}
				?>
				<form action="<?= base_url("cancel_processing"); ?>" id="form-processing" method="post">
					<input type="text" name="scan" id="scan" class="form-control text-center" style="font-size:18pt;" placeholder="Scan Process Barcode">
					<button class="btn btn-info w-100 mt-3" id="btn-scan"><i class="fas fa-qrcode pr-2"></i>SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>
