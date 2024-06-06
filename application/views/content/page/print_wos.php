<!DOCTYPE html>
<html>
<head>
	<title>PRINT WOS</title>
	<link href="<?= base_url("assets/css/sb-admin-2.min.css") ?>" rel="stylesheet">
	<style type="text/css">
		.table-bordered-black {
			border: 1px solid black !important;
		}
		.table-bordered-black th,
		.table-bordered-black td {
			border: 1px solid black !important;
		}
		@media print {
			body, .content, h1, p, table, td, th {
			color-adjust: exact !important;
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
			}
			.table-bordered-black {
			border: 1px solid black !important;
			}
			.table-bordered-black th,
			.table-bordered-black td {
			border: 1px solid black !important;
			}
			h1 {
			color: #000 !important;
			}
			td {
			background-color: #A9A9A9 !important;
			color: #000 !important;
			}
			td.bg-black {
			background-color: #000000 !important;
			color: #FFFFFF !important;
			}
			td.bg-gray {
			background-color: #A9A9A9 !important;
			color: #000000 !important;
			}
		}
	</style>
	<script>
		function printAndClose() {
			// Buka dialog cetak
			window.print();
			
			// Tutup tab setelah cetak atau pembatalan cetak
			window.onafterprint = function() {
				window.location.href = '<?= base_url("print_kanban_asi?tanggal=".$this->input->get("tanggal")); ?>';
			};
		}
    </script>
</head>
<body onload="printAndClose()">
	<center>
      	<h1 style="font-size:3rem; font-family:calibri; color:#000;" class="m-3"> WOS SERVICE PART <?= strtoupper(date("d M Y")); ?> </h1>
	</center>
	<table width="100%" class="content table table-bordered-black">
		<tr>
			<td class="pt-1 pb-1 bg-gray" style="padding-left: 0px;" align="center">No</td>
			<td class="pt-1 pb-1 bg-gray" style="padding-left: 0px;" align="center">Model</td>
			<td class="pt-1 pb-1 bg-gray">Part Number</td>
			<td class="pt-1 pb-1 bg-gray">Part Name</td>
			<td class="pt-1 pb-1 bg-gray">Route</td>
			<td class="pt-1 pb-1 bg-gray">Qty</td>
			<td class="pt-1 pb-1 bg-gray">Keterangan</td>
		</tr> 
		<?php
		$tanggal = $this->input->get("tanggal");
		if(!empty($planning)){
		$no = 1;
		$total_qty = 0;
			foreach ($planning as $planning) {
				$part_no = $planning->part_no."-00";
				$total_qty += $planning->qty;
				//GET BREAKDOWN
				$breakdown = $this->model->gd_wos("breakdown_sp","*","Part_Number = '$part_no'","result");
				foreach ($breakdown as $breakdown) {
				$Result_Qty = $planning->qty * $breakdown->Qty;
				?> 
				<tr> 
					<?php
					if($part_no == $breakdown->Breakdown){
					?> 
						<td class="pt-1 pb-1 bg-black" align="center"> <?= $no++; ?> </td>
						<td class="pt-1 pb-1 bg-black" align="center"> <?= $breakdown->Model; ?> </td>
						<td class="pt-1 pb-1 bg-black"> <?= $breakdown->Breakdown; ?> </td>
						<td class="pt-1 pb-1 bg-black"> <?= $breakdown->Part_Name ?> </td>
						<td class="pt-1 pb-1 bg-black"> <?= $breakdown->Route; ?> </td>
						<td class="pt-1 pb-1 bg-black" align="center"> <?= $Result_Qty; ?> </td>
						<td class="pt-1 pb-1 bg-black"> <?= $breakdown->Original_Part; ?> </td> <?php
					} else {
					?> 
						<td class="pt-1 pb-1" style="color:#000;"></td>
						<td class="pt-1 pb-1" style="color:#000;"></td>
						<td class="pt-1 pb-1" style="color:#000;"> <?= $breakdown->Breakdown; ?> </td>
						<td class="pt-1 pb-1" style="color:#000;"> <?= $breakdown->Part_Name ?> </td>
						<td class="pt-1 pb-1" style="color:#000;"> <?= $breakdown->Route; ?> </td>
						<td class="pt-1 pb-1" style="padding-left: 0px; color:#000;" align="center"> <?= $Result_Qty; ?> </td>
						<td class="pt-1 pb-1" style="color:#000;"> <?= $breakdown->Original_Part; ?> </td> <?php
					}
					?>
				</tr> 
				<?php
				}
			}
		}
		?> 
		<tr>
			<td class="pt-1 pb-1 bg-black" colspan="5" style="font-size: 14pt;" align="center">Total</td>
			<td class="pt-1 pb-1 bg-black" style="font-size: 14pt;" align="center"> <?= $total_qty; ?> </td>
		</tr>
	</table>
</body>
</html>
