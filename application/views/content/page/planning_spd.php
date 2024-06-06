<!-- Content Row -->
<div class="row">
	<div class="col-lg-3 mb-3">
		<p class="mb-1">PLANNING DATE :</p>
		<form action="" method="post">
			<div class="input-group">
				<input type="date" name="planning-date" id="planning-date" class="form-control" value="<?= $planning_date; ?>">
				<button class="btn btn-sm btn-info ml-2"><i class="fas fa-magnifying-glass pr-1"></i>Cari</button>
			</div>
		</form>
		<?php
		//CHECK PLANNING WOS
		$plan = $this->model->gd("planning_spd","id","tanggal = '$planning_date'","row");
		if(!empty($plan->id)){
			echo '<button class="btn btn-sm btn-info mt-2 rounded" onclick="setup_wos()" style="position:fixed; bottom:10px; right:10px; z-index:1; font-size:1rem;">Setup WOS Now</button>';
		}
		?>
	</div>
	<div class="col-lg-9 mb-3">
		<?php
		//DATA PLANNING SPD
		$plan_spd = [];
		$plan_spd = $this->model->gd("planning_spd","part_no,qty","tanggal = '".$planning_date."'","result");
		if(!empty($plan_spd)){
			$plan_spd_json = json_encode($plan_spd);
			$plan_spd = json_decode($plan_spd_json,TRUE);
		}
		$data_type = ["SHELL PART" => "#159ebd","UNDER BODY" => "#3022f5","SIDE MEMBER" => "#1bb50d","N/A" => "#fc3f2d"];
		?>
		<div class="row">
			<?php
			foreach ($data_type as $key_type => $value_type) {
				?>
				<div class="col">
					<div class="card" style="min-height:100%;">
						<div class="card-header text-light p-1 text-center" style="background-color:<?= $value_type; ?>"><?= $key_type; ?></div>
						<div class="card-body d-flex justify-content-center align-items-center text-center font-weight-bold p-0">
							<?php
							$data_plan = [];
							if(!empty($plan_spd)){
								foreach ($plan_spd as $key => $value) {
									//CHECK JALUR
									$jalur = $this->model->gd("jalur_spd","jalur","part_no LIKE '%".$value["part_no"]."%'","row");
									if(!empty($jalur->jalur)){
										$jalur = $jalur->jalur;
									}else{
										$jalur = "N/A";
									}
									//CHECK MODEL
									$model = $this->model->gd("master","model","part_no = '".$value["part_no"]."'","row");
									if(!empty($model->model)){
										$model = $model->model;
									}else{
										$model = "N/A";
									}

									if(substr_count($jalur,$key_type) > 0){
										if($model == "D30D"){
											$model = "D52B";
										}
										$data_plan[$model][] = $value["qty"];
									}
								}
							}
							
							//DTAA SUM ARRAY
							$sumPlan = [];
							if(!empty($data_plan)){
								foreach ($data_plan as $key => $value) {
									$sumPlan[$key] = array_sum($value); 
								}
								echo '<table class="table table-bordered m-0">';
								foreach ($sumPlan as $key => $value) {
									echo '<td class="p-0 border" style="font-size:13pt; cursor:pointer;" onclick="searching(this)" data-type="'.$key_type.' '.$key.'"><h6 class="font-weight-bold pt-1 pb-1 bg-secondary text-light">'.$key.'</h6><h6 class="mt-1 mb-1 font-weight-bold">'.$value.'</h6></td>';
								}
								echo '</table>';
							}else{
								echo '<h3 class="font-weight-bold mt-2 mb-2">0</h3>';
							}
							?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			<div class="col-lg-12 text-right mt-2" id="div-reset-filter">
				<button class="btn btn-sm btn-danger" onclick="searching(this)" data-type=""><i class="fas fa-filter-circle-xmark pr-2"></i>Reset Filter</button>
			</div>
		</div>
	</div>
      <div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-bordered table-hover" id="datatable">
				<thead class="thead-light">
					<tr>
						<th style="font-size:12pt;" class="text-center align-middle">NO</th>
						<th style="font-size:12pt;" class="text-center align-middle">PDD</th>
						<th style="font-size:12pt;" class="text-center align-middle">PART NUMBER</th>
						<th style="font-size:12pt;" class="text-center align-middle">PART NAME</th>
						<th style="font-size:12pt;" class="text-center align-middle">MODEL</th>
						<th style="font-size:12pt;" class="text-center align-middle">ED VENDOR</th>
						<th style="font-size:12pt;" class="text-center align-middle">LINE</th>
						<th style="font-size:12pt;" class="text-center align-middle">QTY</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($data)){
						foreach ($data as $data) {
							//DETAIL PART
							$detail_part = $this->model->gd("master","part_name,model,ed_supplier_name","part_no = '$data->part_no'","row");
							if(!empty($detail_part->part_name)){
								$part_name = $detail_part->part_name;
							}else{
								$part_name = "-";
							}
							
							if(!empty($detail_part->model)){
								$model = $detail_part->model;
								if($model == "D30D"){
									$model = "D52B";
								}
							}else{
								$model = "-";
							}
							
							if(!empty($detail_part->ed_supplier_name)){
								if($detail_part->ed_supplier_name == "ADYAWINSA STAMPING INDUSTRIES"){
									$supplier = "ASI";
								}else{
									$supplier = "TMI";
								}
							}else{
								$supplier = "-";
							}

							//JALUR SPD
							$jalur_spd = $this->model->gd("jalur_spd","jalur","part_no LIKE '%$data->part_no%'","row");
							if(!empty($jalur_spd->jalur)){
								$jalur_spd = str_replace("WELDING","",str_replace("(","",str_replace(")","",$jalur_spd->jalur)));
							}else{
								$jalur_spd = "-";
							}

							$model_jalur = str_replace(" ","",$model."-".$jalur_spd);
							$font_row = "#000000";
							if($model_jalur == "D52B-SHELLPART" || $model_jalur == "D30D-SHELLPART"){
								$color_row = "#e3cb32";
							}else if($model_jalur == "D52B-UNDERBODY" || $model_jalur == "D30D-UNDERBODY"){
								$color_row = "#2fa4ed";
								$font_row = "#ffffff";
							}else if($model_jalur == "D52B-SIDEMEMBER" || $model_jalur == "D30D-SIDEMEMBER"){
								$color_row = "#21db62";
							}else if($model_jalur == "D55L-SHELLPART"){
								$color_row = "#7ded64";
							}else if($model_jalur == "D55L-UNDERBODY"){
								$color_row = "#d5f28a";
							}else if($model_jalur == "D55L-SIDEMEMBER"){
								$color_row = "#f5c8a2";
							}else if($model_jalur == "D74A-SHELLPART"){
								$color_row = "#f2aea5";
							}else if($model_jalur == "D74A-UNDERBODY"){
								$color_row = "#f28dc4";
							}else if($model_jalur == "D74A-SIDEMEMBER"){
								$color_row = "#db93ed";
							}else{
								$color_row = "#ffffff";
							}
							?>
							<tr>
								<td style="font-size:12pt; background-color:<?= $color_row; ?>; color:<?= $font_row; ?>" class="p-1 align-middle text-center font-weight-bold"></td>
								<td style="font-size:12pt; background-color:<?= $color_row; ?>; color:<?= $font_row; ?>" class="p-1 align-middle text-center font-weight-bold"><?= date("d-M-Y",strtotime($data->pdd)); ?></td>
								<td style="font-size:12pt; background-color:<?= $color_row; ?>; color:<?= $font_row; ?>" class="p-1 align-middle text-center font-weight-bold"><?= $data->part_no; ?></td>
								<td style="font-size:12pt; background-color:<?= $color_row; ?>; color:<?= $font_row; ?>" class="p-1 align-middle text-center font-weight-bold"><?= $part_name; ?></td>
								<td style="font-size:12pt; background-color:<?= $color_row; ?>; color:<?= $font_row; ?>" class="p-1 align-middle text-center font-weight-bold"><?= $model; ?></td>
								<td style="font-size:12pt; background-color:<?= $color_row; ?>; color:<?= $font_row; ?>" class="p-1 align-middle text-center font-weight-bold"><?= $supplier; ?></td>
								<td style="font-size:12pt; background-color:<?= $color_row; ?>; color:<?= $font_row; ?>" class="p-1 align-middle text-center font-weight-bold"><?= $jalur_spd; ?></td>
								<td style="font-size:12pt; background-color:<?= $color_row; ?>; color:<?= $font_row; ?>" class="p-1 align-middle text-center font-weight-bold"><?= $data->qty; ?></td>
							</tr>
							<?php
						}
					}
					$count_cutting = 0;
					$count_single_part = 0;
					$count_sub_assy = 0;
					$count_na = 0;
					?>
				</tbody>
			</table>
			<span id="count-cutting"><?= $count_cutting; ?></span>
			<span id="count-single-part"><?= $count_single_part; ?></span>
			<span id="count-sub-assy"><?= $count_sub_assy; ?></span>
			<span id="count-na"><?= $count_na; ?></span>
		</div>
	</div>
</div>

