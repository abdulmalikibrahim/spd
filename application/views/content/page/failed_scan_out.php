<style>
	.swal2-html-container{
		overflow:hidden !important;
	}
</style>
<!-- Content Row -->
<div class="row">
	<?php
	$data_type = ["SUB-ASSY","CUTTING","SINGLE PART","N/A"];
	if(!empty($data)){
		$dataJson = json_encode($data);
		$dataJson = json_decode($dataJson,true);
	}else{
		$dataJson = [];
	}
	foreach ($data_type as $key => $value) {
		?>
		<div class="col-lg-12 mb-5">
			<div class="table-responsive" style="max-height:500px;">
				<h5>DATA <?= $value; ?></h5>
				<table class="table table-bordered table-hover datatable">
					<thead class="thead-light">
						<tr>
							<th style="font-size:8pt;" class="text-center">No</th>
							<th style="font-size:8pt; min-width:130px;" class="text-center">Part No</th>
							<th style="font-size:8pt; min-width:300px;" class="text-center">Part Name</th>
							<th style="font-size:8pt;" class="text-center">Model</th>
							<th style="font-size:8pt;" class="text-center">Qty</th>
							<th style="font-size:8pt; min-width:130px;" class="text-center">PDD</th>
							<th style="font-size:8pt; min-width:130px;" class="text-center">Jalur</th>
							<th style="font-size:8pt; min-width:100px;" class="text-center">PO ED</th>
							<th style="font-size:8pt; min-width:100px;" class="text-center">PO STO</th>
							<th style="font-size:8pt;" class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($dataJson as $key => $data) {
							if(!empty($data["part_no"])){
								$part_no = $data["part_no"];
							}else{
								$part_no = "";
							}

							$type = $this->model->gd("jalur_spd","type,jalur","part_no LIKE '%".$part_no."%'","row");
							if(!empty($type->type)){
								$type_item = $type->type;
							}else{
								$type_item = "N/A";
							}
							
							if(!empty($type->jalur)){
								$jalur_item = $type->jalur;
							}else{
								$jalur_item = "N/A";
							}
							if($type_item == $value){
								$check_remain = $this->model->gd("master","remain_out_plant","po_ed = '".$data["po_ed"]."' AND po_sto = '".$data["po_sto"]."' AND finish_delivery = '".$data["pdd"]."' AND part_no = '".$part_no."'","row");
								if($check_remain->remain_out_plant > 0){
									$detail_part = $this->model->gd("master","model,part_name","part_no = '".$part_no."'","row");
									$part_name = '<span class="badge badge-warning">N/A</span>';
									$model = '<span class="badge badge-warning">N/A</span>';
									if(!empty($detail_part->model)){
										$model = $detail_part->model;
									}
									if(!empty($detail_part->part_name)){
										$part_name = $detail_part->part_name;
									}
									?>
									<tr>
										<td style="font-size:8pt;" class="text-center"><?= $no++; ?></td>
										<td style="font-size:8pt;" class="text-center"><?= $part_no; ?></td>
										<td style="font-size:8pt;" id="part-name-<?= $part_no."-1-".$data["po_ed"]."-".$data["po_sto"]."-".date("Ymd",strtotime($data["pdd"])); ?>"><?= $part_name; ?></td>
										<td style="font-size:8pt;" class="text-center"><?= $model; ?></td>
										<td style="font-size:8pt;" class="text-center"><?= $data["qty_total"]; ?></td>
										<td style="font-size:8pt;" class="text-center"><?= date("d-M-Y",strtotime($data["pdd"])); ?></td>
										<td style="font-size:8pt;" class="text-center"><?= $jalur_item; ?></td>
										<td style="font-size:8pt;" class="text-center"><?= $data["po_ed"]; ?></td>
										<td style="font-size:8pt;" class="text-center"><?= $data["po_sto"]; ?></td>
										<td style="font-size:8pt;" class="text-center"><a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="cancelScanOut('<?= e_nzm($part_no.' 1 '.$data['po_ed'].' '.$data['po_sto'].' '.date('Ymd',strtotime($data['pdd']))); ?>')">Cancel</a></td>
									</tr>
									<?php
								}
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
	?>
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

