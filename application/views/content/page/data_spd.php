<!-- Content Row -->
<div class="row">
	<div class="col-lg-4 mb-3">
		<p class="mb-1">PDD :</p>
		<form action="" method="post">
			<div class="input-group">
				<input type="date" name="start-date" id="start-date" class="form-control" value="<?= $start_date; ?>">
				<input type="date" name="end-date" id="end-date" class="form-control" value="<?= $end_date; ?>">
				<button class="btn btn-sm btn-info ml-2"><i class="fas fa-magnifying-glass pr-1"></i>Cari</button>
			</div>
		</form>
		<div class="card rounded" id="card-process" style="position:fixed; bottom:10px; right:10px; z-index:1;">
			<div class="card-body bg-info text-light p-2 rounded">
				<h4 class="font-weight-bold m-0">QTY PROCESS : <span id="qty_process">0</span></h4>
				<button class="btn btn-sm btn-light mt-2" onclick="process_wos()">Process Now</button>
			</div>
		</div>
		<?php
		//CHECK PLANNING WOS
		$plan = $this->model->gd("planning_spd","id","already_print = '0'","row");
		if(!empty($plan->id)){
			echo '<button class="btn btn-sm btn-info mt-2 rounded" onclick="setup_wos()" style="position:fixed; bottom:10px; right:10px; z-index:1; font-size:2rem;">Setup WOS Now</button>';
		}
		?>
	</div>
	<div class="col-lg-12 mb-3">
		<?php
			if(!empty($data)){
				$count_cutting = 0;
				$count_single_part = 0;
				$count_sub_assy = 0;
				$count_by_vendor_sa = [];
				$count_by_vendor_sa_model = [];
				$count_by_vendor_sa_model_jalur = [];
				$count_by_vendor_cut = [];
				$count_by_vendor_cut_model = [];
				$count_by_vendor_sp = [];
				$count_by_vendor_sp_model = [];
				$count_by_vendor_na = [];
				$count_by_vendor_na_model = [];
				$count_na = 0;
				foreach ($data as $data_count) {
					$detail_part = $this->model->gd("jalur_spd","type,jalur","part_no LIKE '%".$data_count->part_no."%'","row");
					if(!empty($detail_part->type)){
						$type = $detail_part->type;
						if($type == "SINGLE PART"){
							$data_count_sa = 0;
							if($this->p1 == "spd_all" || $this->p1 == "spd_close"){
								$count_single_part += $data_count->qty_plan;
								$data_count_sp = $data_count->qty_plan;
							}else if($this->p1 == "spd_remain"){
								$count_single_part += $data_count->remain_out_plant;
								$data_count_sp = $data_count->remain_out_plant;
							}
							$count_by_vendor_sp[$data_count->ed_supplier_name][] = $data_count_sp;
							$count_by_vendor_sp_model[$data_count->ed_supplier_name][$data_count->model][] = $data_count_sp;
						}else if($type == "CUTTING"){
							if($this->p1 == "spd_all" || $this->p1 == "spd_close"){
								$count_cutting += $data_count->qty_plan;
								$data_count_cut = $data_count->qty_plan;
							}else if($this->p1 == "spd_remain"){
								$count_cutting += $data_count->remain_out_plant;
								$data_count_cut = $data_count->remain_out_plant;
							}
							$count_by_vendor_cut[$data_count->ed_supplier_name][] = $data_count_cut;
							$count_by_vendor_cut_model[$data_count->ed_supplier_name][$data_count->model][] = $data_count_cut;
						}if($type == "SUB-ASSY"){
							if($this->p1 == "spd_all" || $this->p1 == "spd_close"){
								$count_sub_assy += $data_count->qty_plan;
								$data_count_sa = $data_count->qty_plan;
							}else if($this->p1 == "spd_remain"){
								$count_sub_assy += $data_count->remain_out_plant;
								$data_count_sa = $data_count->remain_out_plant;
							}
							$count_by_vendor_sa[$data_count->ed_supplier_name][] = $data_count_sa;
							$count_by_vendor_sa_model[$data_count->ed_supplier_name][$data_count->model][] = $data_count_sa;
							$count_by_vendor_sa_model_jalur[$data_count->ed_supplier_name][$data_count->model][$detail_part->jalur][] = $data_count_sa;
						}
					}else{
						if($this->p1 == "spd_all" || $this->p1 == "spd_close"){
							$count_na += $data_count->qty_plan;
							$data_count_na = $data_count->qty_plan;
						}else if($this->p1 == "spd_remain"){
							$count_na += $data_count->remain_out_plant;
							$data_count_na = $data_count->remain_out_plant;
						}
						$count_by_vendor_na[$data_count->ed_supplier_name][] = $data_count_na;
						$count_by_vendor_na_model[$data_count->ed_supplier_name][$data_count->model][] = $data_count_na;
					}
				}
				asort($count_by_vendor_sa);
				asort($count_by_vendor_sa_model);
				asort($count_by_vendor_cut);
				asort($count_by_vendor_cut_model);
				asort($count_by_vendor_sp);
				asort($count_by_vendor_sp_model);
				asort($count_by_vendor_na);
				asort($count_by_vendor_na_model);
			}
		?>
		<div class="row">
			<div class="col-lg-4 col-6">
				<div class="card border-dark">
					<div class="card-header border-dark bg-warning text-dark p-1 text-center">SUB-ASSY</div>
					<div class="card-body text-center font-weight-bold p-0">
						<div style="font-size:13pt; cursor:pointer;" class="m-2" onclick="searching(this)" data-type="DATA#SPD-SUB-ASSY">
							<?= $count_sub_assy; ?>
						</div>
						<table class="table table-bordered m-0">
							<tr>
								<?php
								if(!empty($count_by_vendor_sa)){
									foreach ($count_by_vendor_sa as $key => $value) {
										if($key == "ADYAWINSA STAMPING INDUSTRIES"){
											$name_supplier = "ASI";
										}else{
											$name_supplier = "TMI";
										}
										echo '<td style="font-size:10pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SUB-ASSY '.$key.'" class="p-0 pt-1 pb-1 bg-secondary text-light rounded">'.$name_supplier.'</td>';
									}
								}
								?>
							</tr>
							<tr>
								<?php
								$total_vendor = [];
								if(!empty($count_by_vendor_sa)){
									foreach ($count_by_vendor_sa as $key => $value) {
										$total_vendor[$key] = array_sum($value);
										echo '<td style="font-size:10pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SUB-ASSY '.$key.'" class="p-0 pt-1 pb-1 rounded">'.array_sum($value).'</td>';
									}
								}
								?>
							</tr>
							<tr>
								<?php
								if(!empty($count_by_vendor_sa)){
									foreach ($count_by_vendor_sa as $key_vendor => $value_vnedor) {
										?>
										<td class="p-0">
											<table class="table table-bordered m-0">
												<tr>
													<?php
													if(!empty($count_by_vendor_sa_model[$key_vendor])){
														foreach ($count_by_vendor_sa_model[$key_vendor] as $model => $value) {
															echo '<td style="font-size:8pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SUB-ASSY '.$key_vendor.' '.$model.'" class="p-0 pt-1 pb-1 bg-secondary text-light rounded">'.$model.'</td>';
														}
													}
													?>
												</tr>
												<tr>
													<?php
													if(!empty($count_by_vendor_sa_model[$key_vendor])){
														foreach ($count_by_vendor_sa_model[$key_vendor] as $model => $value) {
															echo '<td style="font-size:8pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SUB-ASSY '.$key_vendor.' '.$model.'" class="p-0 pt-1 pb-1 rounded">'.array_sum($value).'</td>';
														}
													}
													?>
												</tr>
												<tr>
													<?php
													$average_normal = [];
													foreach ($count_by_vendor_sa_model_jalur[$key_vendor] as $k_avg => $v_avg) {
														foreach ($v_avg as $k_avg1 => $v_avg1) {
															$sum_avg = array_sum($v_avg1);
															$average_normal[$key_vendor][] = $sum_avg;
														}
													}
													$average_normal_count = count($average_normal[$key_vendor]);
													$avg_normal = round(array_sum($average_normal[$key_vendor]) / $average_normal_count,0);
													foreach ($count_by_vendor_sa_model[$key_vendor] as $model => $value) {
														asort($count_by_vendor_sa_model_jalur[$key_vendor][$model]);
														?>
														<td class="p-0">
															<table class="table table-bordered m-0">
																<tr>
																	<?php
																	foreach ($count_by_vendor_sa_model_jalur[$key_vendor][$model] as $jalur => $value) {
																		if($jalur == "WELDING (SHELL PART)"){
																			$nama_jalur = "S/P";
																		}else if($jalur == "WELDING (UNDER BODY)"){
																			$nama_jalur = "U/B";
																		}else if($jalur == "WELDING (SIDE MEMBER)"){
																			$nama_jalur = "S/M";
																		}
																		echo '<td style="font-size:6.5pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SUB-ASSY '.$key_vendor.' '.$model.' '.$jalur.'" class="p-0 pt-1 pb-1 bg-secondary text-light rounded">'.$nama_jalur.'</td>';
																	}
																	?>
																</tr>
																<tr>
																	<?php
																	foreach ($count_by_vendor_sa_model_jalur[$key_vendor][$model] as $jalur => $value) {
																		$total_per_shop = array_sum($value);
																		if($total_per_shop > $avg_normal){
																			$color = "bg-danger text-light";
																		}else{
																			$color = "";
																		}
																		$nama_jalur = str_replace("WELDING (","",str_replace(")","",$jalur));
																		echo '<td style="font-size:6.5pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SUB-ASSY '.$key_vendor.' '.$model.' '.$jalur.'" class="p-0 pt-1 pb-1 '.$color.' rounded">'.$total_per_shop.'</td>';
																	}
																	?>
																</tr>
															</table>
														</td>
														<?php
													}
													?>
												</tr>
											</table>
										</td>
										<?php
									}
								}
								?>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="row">
					<div class="col-lg mb-2">
						<div class="card border-dark">
							<div class="card-header border-dark bg-success text-light p-1 text-center">CUTTING</div>
							<div class="card-body text-center font-weight-bold p-0">
								<div style="font-size:13pt; cursor:pointer;" class="m-2" onclick="searching(this)" data-type="DATA#SPD-CUTTING">
									<?= $count_cutting; ?>
								</div>
								<table class="table table-bordered m-0">
									<tr>
										<?php
										if(!empty($count_by_vendor_cut)){
											foreach ($count_by_vendor_cut as $key => $value) {
												if($key == "ADYAWINSA STAMPING INDUSTRIES"){
													$name_supplier = "ASI";
												}else{
													$name_supplier = "TMI";
												}
												echo '<td style="font-size:10pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-CUTTING '.$key.'" class="p-0 pt-1 pb-1 bg-secondary text-light">'.$name_supplier.'</td>';
											}
										}
										?>
									</tr>
									<tr>
										<?php
										if(!empty($count_by_vendor_cut)){
											foreach ($count_by_vendor_cut as $key => $value) {
												echo '<td style="font-size:10pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-CUTTING '.$key.'" class="p-0 pt-1 pb-1">'.array_sum($value).'</td>';
											}
										}
										?>
									</tr>
									<tr>
										<?php
										if(!empty($count_by_vendor_cut)){
											foreach ($count_by_vendor_cut as $key_vendor => $value_vnedor) {
												?>
												<td class="p-0">
													<table class="table table-bordered m-0">
														<tr>
															<?php
															if(!empty($count_by_vendor_cut_model[$key_vendor])){
																foreach ($count_by_vendor_cut_model[$key_vendor] as $model => $value) {
																	echo '<td style="font-size:8pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-CUTTING '.$key_vendor.' '.$model.'" class="p-0 pt-1 pb-1 bg-secondary text-light">'.$model.'</td>';
																}
															}
															?>
														</tr>
														<tr>
															<?php
															if(!empty($count_by_vendor_cut_model[$key_vendor])){
																foreach ($count_by_vendor_cut_model[$key_vendor] as $model => $value) {
																	echo '<td style="font-size:8pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-CUTTING '.$key_vendor.' '.$model.'" class="p-0 pt-1 pb-1">'.array_sum($value).'</td>';
																}
															}
															?>
														</tr>
													</table>
												</td>
												<?php
											}
										}
										?>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="col-lg mb-2">
						<div class="card border-dark">
							<div class="card-header border-dark bg-info text-light p-1 text-center">SINGLE PART</div>
							<div class="card-body text-center font-weight-bold p-0">
								<div style="font-size:13pt; cursor:pointer;" class="m-2" onclick="searching(this)" data-type="DATA#SPD-SINGLE PART">
									<?= $count_single_part; ?>
								</div>
								<table class="table table-bordered m-0">
									<tr>
										<?php
										if(!empty($count_by_vendor_sp)){
											foreach ($count_by_vendor_sp as $key => $value) {
												if($key == "ADYAWINSA STAMPING INDUSTRIES"){
													$name_supplier = "ASI";
												}else{
													$name_supplier = "TMI";
												}
												echo '<td style="font-size:10pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SINGLE PART '.$key.'" class="p-0 pt-1 pb-1 bg-secondary text-light">'.$name_supplier.'</td>';
											}
										}
										?>
									</tr>
									<tr>
										<?php
										if(!empty($count_by_vendor_sp)){
											foreach ($count_by_vendor_sp as $key => $value) {
												echo '<td style="font-size:10pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SINGLE PART '.$key.'" class="p-0 pt-1 pb-1">'.array_sum($value).'</td>';
											}
										}
										?>
									</tr>
									<tr>
										<?php
										if(!empty($count_by_vendor_sp)){
											foreach ($count_by_vendor_sp as $key_vendor => $value_vnedor) {
												?>
												<td class="p-0">
													<table class="table table-bordered m-0">
														<tr>
															<?php
															if(!empty($count_by_vendor_sp_model[$key_vendor])){
																foreach ($count_by_vendor_sp_model[$key_vendor] as $model => $value) {
																	echo '<td style="font-size:8pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SINGLE PART '.$key_vendor.' '.$model.'" class="p-0 pt-1 pb-1 bg-secondary text-light">'.$model.'</td>';
																}
															}
															?>
														</tr>
														<tr>
															<?php
															if(!empty($count_by_vendor_sp_model[$key_vendor])){
																foreach ($count_by_vendor_sp_model[$key_vendor] as $model => $value) {
																	echo '<td style="font-size:8pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-SINGLE PART '.$key_vendor.' '.$model.'" class="p-0 pt-1 pb-1">'.array_sum($value).'</td>';
																}
															}
															?>
														</tr>
													</table>
												</td>
												<?php
											}
										}
										?>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="col-lg mb-2">
						<div class="card border-dark">
							<div class="card-header border-dark bg-danger text-light p-1 text-center">N/A</div>
							<div class="card-body text-center font-weight-bold p-0">
								<div style="font-size:13pt; cursor:pointer;" class="m-2" onclick="searching(this)" data-type="DATA#SPD-N/A">
									<?= $count_na; ?>
								</div>
								<table class="table table-bordered m-0">
									<tr>
										<?php
										if(!empty($count_by_vendor_na)){
											foreach ($count_by_vendor_na as $key => $value) {
												if($key == "ADYAWINSA STAMPING INDUSTRIES"){
													$name_supplier = "ASI";
												}else{
													$name_supplier = "TMI";
												}
												echo '<td style="font-size:10pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-N/A '.$key.'" class="p-0 pt-1 pb-1 bg-secondary text-light">'.$name_supplier.'</td>';
											}
										}
										?>
									</tr>
									<tr>
										<?php
										if(!empty($count_by_vendor_na)){
											foreach ($count_by_vendor_na as $key => $value) {
												echo '<td style="font-size:10pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-N/A '.$key.'" class="p-0 pt-1 pb-1">'.array_sum($value).'</td>';
											}
										}
										?>
									</tr>
									<tr>
										<?php
										if(!empty($count_by_vendor_na)){
											foreach ($count_by_vendor_na as $key_vendor => $value_vnedor) {
												?>
												<td class="p-0">
													<table class="table table-bordered m-0">
														<tr>
															<?php
															if(!empty($count_by_vendor_na_model[$key_vendor])){
																foreach ($count_by_vendor_na_model[$key_vendor] as $model => $value) {
																	echo '<td style="font-size:8pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-N/A '.$key_vendor.' '.$model.'" class="p-0 pt-1 pb-1 bg-secondary text-light">'.$model.'</td>';
																}
															}
															?>
														</tr>
														<tr>
															<?php
															if(!empty($count_by_vendor_na_model[$key_vendor])){
																foreach ($count_by_vendor_na_model[$key_vendor] as $model => $value) {
																	echo '<td style="font-size:8pt; cursor:pointer;" onclick="searching(this)" data-type="DATA#SPD-N/A '.$key_vendor.' '.$model.'" class="p-0 pt-1 pb-1">'.array_sum($value).'</td>';
																}
															}
															?>
														</tr>
													</table>
												</td>
												<?php
											}
										}
										?>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<?php
					$now_date = date("d");
					$end_date = date("t");
					$sisa_hari = $end_date - $now_date;
					?>
					<div class="col-lg-6">
						<div class="card border-dark">
							<div class="card-body bg-warning text-dark d-flex align-items-center p-0 pl-3 font-weight-bold">
								<i class="fas fa-cloud-sun pr-2" style="font-size:1.5rem; margin:5px 0 5px 0;"></i>
								Target Prod Day (SUB-ASSY) : <?= round(($count_sub_assy/$sisa_hari)*(60/100),0); ?>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card border-dark">
							<div class="card-body bg-secondary text-light d-flex align-items-center p-0 pl-3 font-weight-bold">
								<i class="fas fa-cloud-moon pr-2" style="font-size:1.5rem; margin:5px 0 5px 0;"></i>
								Target Prod Night (SUB-ASSY) : <?= round(($count_sub_assy/$sisa_hari)*(40/100),0); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-12 text-right mt-2" id="div-reset-filter">
				<button class="btn btn-sm btn-danger" onclick="searching(this)" data-type=""><i class="fas fa-filter-circle-xmark pr-2"></i>Reset Filter</button>
			</div>
		</div>
	</div>
      <div class="col-lg-12">
		<div class="table-responsive" id="datatable-div">
			<form action="<?= base_url("create_wos"); ?>" method="post" id="form-wos">
				<table class="table table-bordered table-hover" id="datatable">
					<thead class="thead-light">
						<tr>
							<th style="font-size:9pt;" class="text-center align-middle" rowspan="2">No</th>
							<th style="font-size:9pt;" class="text-center align-middle" rowspan="2"></th>
							<th style="font-size:9pt;" class="text-center align-middle" rowspan="2">PO</th>
							<th style="font-size:9pt;" class="text-center align-middle" rowspan="2">Plant</th>
							<th style="font-size:9pt; min-width:130px;" class="text-center align-middle" rowspan="2">Part No</th>
							<th style="font-size:9pt; min-width:300px;" class="text-center align-middle" rowspan="2">Part Name</th>
							<th style="font-size:9pt; min-width:100px;" class="text-center align-middle" rowspan="2">Job No</th>
							<th style="font-size:9pt;" class="text-center align-middle" rowspan="2">Model</th>
							<th style="font-size:9pt;" class="text-center align-middle" rowspan="2">Qty</th>
							<th style="font-size:9pt; min-width:100px;" class="text-center align-middle" rowspan="2">Type</th>
							<th style="font-size:9pt; min-width:200px;" class="text-center align-middle" rowspan="2">Jalur</th>
							<th style="font-size:9pt; min-width:130px;" class="text-center align-middle" rowspan="2">PDD</th>
							<th style="font-size:9pt; max-width:60px;" class="text-center align-middle" rowspan="2">ROP</th>
							<th style="font-size:9pt; max-width:120px;" class="text-center align-middle" colspan="2">Process</th>
							<th style="font-size:9pt; min-width:330px;" class="text-center align-middle" rowspan="2">Supplier Name</th>
							<th style="font-size:9pt; min-width:250px;" class="text-center align-middle" rowspan="2">ADM Routing</th>
							<th style="font-size:9pt; min-width:250px;" class="text-center align-middle" rowspan="2">Action</th>
						</tr>
						<tr>
							<th style="font-size:9pt; min-width:60px;" class="text-center align-middle">Done</th>
							<th style="font-size:9pt; min-width:60px;" class="text-center align-middle">Remain</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(!empty($data)){
							$no = 1;
							foreach ($data as $data) {
								if($data->remain_out_plant > 0){
									$color_row = 'bg-danger';
									$remain_out_plant = $data->remain_out_plant;
								}else{
									$color_row = 'bg-success';
									$remain_out_plant = "Close";
								}
								$detail_part = $this->model->gd("jalur_spd","type,jalur","part_no LIKE '%".$data->part_no."%'","row");
								$type = '<span class="badge badge-danger">N/A</span>';
								$jalur = '<span class="badge badge-danger">N/A</span>';
								if(!empty($detail_part->type)){
									$type = $detail_part->type;
								}

								if(!empty($detail_part->jalur)){
									$jalur = $detail_part->jalur;
								}

								if(!empty($data->total_process)){
									$count_already_process = $data->total_process;
								}else{
									$count_already_process = 0;
								}
								if($count_already_process > 0){
									$bg_cell_already_process = "bg-success";
									$val_count_already_process = $count_already_process;
								}else{
									$val_count_already_process = "-";
									$bg_cell_already_process = "bg-danger";
								}

								if(!empty($data->total_remain)){
									$remain_process = $data->total_remain;
								}else{
									$remain_process = 0;
								}
								if($remain_process > 0){
									$bg_cell_remain_process = "bg-danger";
									$val_remain_process = $remain_process;
								}else{
									$bg_cell_remain_process = "bg-success";
									$val_remain_process = "-";
								}
								?>
								<tr>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $no++; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><input type="checkbox" data-id="<?= e_nzm($data->id); ?>" id="plan-<?= e_nzm($data->id); ?>" onclick="sum_qty(this)" data-qty="<?= $remain_process; ?>" name="plan[]" value="<?= $data->part_no."#".$data->po_sto."#".$data->finish_delivery."#".$remain_process."#".$data->po_ed; ?>"></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->po_sto; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->plant; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->part_no; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle"><?= $data->part_name; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->job_no; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->model; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->qty_plan; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $type; ?><font style="font-size:0pt;">DATA#SPD-<?= $type; ?></font></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $jalur; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->finish_delivery; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle <?= $color_row; ?> text-light text-center"><?= $remain_out_plant; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle <?= $bg_cell_already_process; ?> text-light text-center"><?= $val_count_already_process; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle <?= $bg_cell_remain_process; ?> text-light text-center"><?= $val_remain_process; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->ed_supplier_name; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center"><?= $data->adm_routing; ?></td>
									<td style="font-size:9pt;" class="p-1 align-middle text-center">
										<a href="<?= base_url("print_kanban?id=".e_nzm($data->id)); ?>" class="btn btn-sm btn-info" title="Print Kanban" target="_blank"><i class="fas fa-print"></i></a>
										<a onclick="loading_page('Merubah Status...','Mohon tunggu sampai proses selesai.',true)" href="<?= base_url("already_process?id=".e_nzm($data->id))."&back=".$this->p1; ?>" class="btn btn-sm btn-success" title="Already Process"><i class="fas fa-check-circle"></i></a>
									</td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>

