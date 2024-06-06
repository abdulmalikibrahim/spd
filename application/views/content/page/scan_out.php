<style>
	.swal2-html-container{
		overflow:hidden !important;
	}
</style>
<!-- Content Row -->
<div class="row">
	<div class="col-lg-12 text-right mb-2"><button class="btn btn-sm btn-info" id="btn-scan-out" onclick="submitCheckboxData()">Scan Out Now</button></div>
      <div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-bordered table-hover" id="datatable">
				<thead class="thead-light">
					<tr>
						<th style="font-size:9pt;" class="text-center">No</th>
						<th style="font-size:9pt;" class="text-center"><input type="checkbox" name="check-all" id="check-all" onclick="toggleCheckboxes(this)"></th>
						<th style="font-size:9pt; min-width:130px;" class="text-center">Part No</th>
						<th style="font-size:9pt; min-width:300px;" class="text-center">Part Name</th>
						<th style="font-size:9pt;" class="text-center">Model</th>
						<th style="font-size:9pt;" class="text-center">Vendor</th>
						<th style="font-size:9pt; min-width:100px;" class="text-center">PO ED</th>
						<th style="font-size:9pt; min-width:100px;" class="text-center">PO STO</th>
						<th style="font-size:9pt; min-width:130px;" class="text-center">PDD</th>
						<th style="font-size:9pt;" class="text-center">Qty</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($data)){
						$no = 1;
						foreach ($data as $data) {
							$detail_part = $this->model->gd("master","model,part_name,ed_supplier_name","part_no = '".$data->part_no."'","row");
							$part_name = '<span class="badge badge-warning">N/A</span>';
							$model = '<span class="badge badge-warning">N/A</span>';
							if(!empty($detail_part->model)){
								$model = $detail_part->model;
							}
							if(!empty($detail_part->part_name)){
								$part_name = $detail_part->part_name;
							}
							if($detail_part->ed_supplier_name == "ADYAWINSA STAMPING INDUSTRIES"){
								$supplier = "ASI";
							}else{
								$supplier = "TMI";
							}
							?>
							<tr>
								<td style="font-size:9pt;" class="text-center"><?= $no++; ?></td>
								<td style="font-size:9pt;" class="text-center"><input type="checkbox" class="check-data" name="check-data[]" value="<?= $data->part_no." 1 ".$data->po_ed." ".$data->po_sto." ".date("Ymd",strtotime($data->pdd)); ?>"></td>
								<td style="font-size:9pt;" class="text-center"><?= $data->part_no; ?></td>
								<td style="font-size:9pt;" id="part-name-<?= $data->part_no."-1-".$data->po_ed."-".$data->po_sto."-".date("Ymd",strtotime($data->pdd)); ?>"><?= $part_name; ?></td>
								<td style="font-size:9pt;" class="text-center"><?= $model; ?></td>
								<td style="font-size:9pt;" class="text-center"><?= $supplier; ?></td>
								<td style="font-size:9pt;" class="text-center"><?= $data->po_ed; ?></td>
								<td style="font-size:9pt;" class="text-center"><?= $data->po_sto; ?></td>
								<td style="font-size:9pt;" class="text-center"><?= date("d-M-Y",strtotime($data->pdd)); ?></td>
								<td style="font-size:9pt;" class="text-center"><?= $data->qty_total; ?></td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Modal body text goes here.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

