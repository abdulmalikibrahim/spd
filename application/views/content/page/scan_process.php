<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-body">
				<?php
				if(!empty($this->session->flashdata("notif"))){
					echo $this->session->flashdata("notif");
				}
				?>
				<form action="<?= base_url("scan_processing"); ?>" id="form-processing" method="post">
					<input type="text" name="scan" id="scan" class="form-control text-center" style="font-size:18pt;" placeholder="Scan Process Barcode">
					<button class="btn btn-info w-100 mt-3" id="btn-scan"><i class="fas fa-qrcode pr-2"></i>SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="card">
			<div class="card-header">List Scan <span class="font-weight-bold text-info">(<?= count($data); ?> kanban)</span></div>
			<div class="card-body" style="max-height:400px; overflow:auto;">
				<table class="w-100">
					<?php
					if(!empty($data)){
						$no = 1;
						foreach ($data as $data) {
							echo '
							<tr>
								<td style="font-size:11pt;" class="pb-2">'.$no++.'.</td>
								<td style="font-size:11pt;" class="pb-2">'.$data->barcode.'</td>
								<td style="font-size:11pt;" class="pb-2"><a class="btn btn-danger btn-circle btn-sm" id="btn-cancel-'.e_nzm($data->id).'" data-id="'.e_nzm($data->id).'" data-url="'.base_url("cancel_processing?barcode=".e_nzm($data->barcode)).'" onclick="cancel_process(this)"><i class="fas fa-trash-alt text-light"></i></a></td>
							</tr>';
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
</div>
