<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PRINT KANBAN TMI</title>
	<style type="text/css">
		* {
			margin: 0;
			padding: 0;
			text-indent: 0;
		}

		.s1 {
			color: black;
			font-family: "Bookman Old Style", serif;
			font-style: normal;
			font-weight: bold;
			text-decoration: none;
			font-size: 6pt;
		}

		.s2 {
			color: black;
			font-family: "Bookman Old Style", serif;
			font-style: normal;
			font-weight: bold;
			text-decoration: none;
			font-size: 12.5pt;
		}

		.s3 {
			color: black;
			font-family: "Bookman Old Style", serif;
			font-style: normal;
			font-weight: bold;
			text-decoration: none;
			font-size: 9.5pt;
		}

		.s4 {
			color: black;
			font-family: "Bookman Old Style", serif;
			font-style: normal;
			font-weight: bold;
			text-decoration: none;
			font-size: 6.5pt;
		}

		.s5 {
			color: black;
			font-family: "Bookman Old Style", serif;
			font-style: normal;
			font-weight: bold;
			text-decoration: none;
			font-size: 7.5pt;
		}

		.s6 {
			color: black;
			font-family: "Bookman Old Style", serif;
			font-style: normal;
			font-weight: bold;
			text-decoration: none;
			font-size: 9pt;
		}

		.s7 {
			color: black;
			font-family: Stencil;
			font-style: normal;
			font-weight: normal;
			text-decoration: none;
			font-size: 5pt;
		}

		.s8 {
			color: black;
			font-family: "Bookman Old Style", serif;
			font-style: normal;
			font-weight: bold;
			text-decoration: none;
			font-size: 11pt;
		}
		.dashed-line {
			border: 0;
			border-top: 1px dashed #000; /* Warna dan ketebalan garis */
			margin: 8px 0; /* Margin atas dan bawah */
		}

		table,
		tbody {
			vertical-align: top;
			overflow: visible;
		}
	</style>
	<script>
		function printAndClose() {
			// Buka dialog cetak
			window.print();
			
			// Tutup tab setelah cetak atau pembatalan cetak
			window.onafterprint = function() {
				window.location.href = '<?= base_url("closing_planning"); ?>';
			};
		}
    </script>
</head>
<body onload="printAndClose()" >
	<?php
	function getPreviousBusinessDay($date) {
		// Buat objek DateTime dari tanggal yang diberikan
		$dateTime = new DateTime($date);
		
		// Kurangi satu hari
		$dateTime->modify('-1 day');
		
		// Cek apakah hari jatuh pada Sabtu atau Minggu
		while ($dateTime->format('N') >= 6) {
			// Jika Sabtu atau Minggu, kurangi satu hari lagi
			$dateTime->modify('-1 day');
		}
		
		return $dateTime->format('Y-m-d');
	}
	if(!empty($data_kanban_print)){
		foreach ($data_kanban_print as $data_kanban_print) {
			//CARI SEQ SCAN OUT
			$seq = $this->model->gd("scan_process","seq","part_no = '$data_kanban_print->part_no' AND po_ed = '$data_kanban_print->po_ed' AND po_sto = '$data_kanban_print->po_sto' AND pdd = '$data_kanban_print->pdd'","result");
			$seq_json = json_encode($seq);
			
			$data_kanban = $this->model->gd("master","*","part_no = '$data_kanban_print->part_no' AND po_ed = '$data_kanban_print->po_ed' AND po_sto = '$data_kanban_print->po_sto' AND finish_delivery = '$data_kanban_print->pdd'","row");
			if($data_kanban->ed_supplier_name == "TAIKISHA MANUFACTURING INDONESIA"){
				if(!empty($data_kanban_print->qty)){
					for ($i=1; $i <= $data_kanban_print->qty; $i++) {
						$seq_kanban = '"seq":"'.$i.'"';
						if(substr_count($seq_json,$seq_kanban) <= 0){
							if($data_kanban->customer == "DH"){
								$customer = "ADM";
							}else if($data_kanban->customer == "TI"){
								$customer = "TMMIN";
							}else{
								$customer = "TAM";
							}
							echo '
							<table style="border-collapse:collapse;margin-left:5.991pt; margin-top:5.991pt; min-width: 98%; height: 201px;" cellspacing="0">
								<tr style="height:18pt">
									<td style="width:44pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt;text-align: center; vertical-align:middle;" rowspan="2">
										<img width="49" height="28" src="data:image/jpg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAAcADEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwDy/TPDmra5bXNxp2mXd9b2xTz5IIWdItxwu4gfKCQQCcA4Poa35Pg34+jxu8F671x8unyn8OBVP4ffEHWPhn4jj1jRpEMm1op7Sdd0F1C2N0Mqk4ZCB7EdeoFfoD8GfjVoPivwkZFlLeFpQLe5t7iXfNokjceTMxOWt25CS9uh4wV+IweFo4lWcmmuh/UnEufZpkclUp0YypN6PX7n+nf1Pz7n+HHi+2/1vhLX4sf39KuB+uzFY2oWF3pBAv7SewY8AXUTR8+nzAV9K/tOfBjxl8FtSbXPD/iPX7jwhcScFdRnL2Lk8JIQ+SpOdrfQE5wT4lYfGXx3pIzD4x1uMEfMJr6SQY68h81jXoQoTcJXXyueplmbYrM8NHFUHTkn5uNn1T31ONV0YZUhvoc05h3PHPevQdL+JHi/xrfGH+yrPxnckhXjk0WK5lIPYtGgcZ9cj61qePPh6mh+FbnUfEfh2DwJrJRHsrGC98w3uXAKm1Ys8ShQzeZvAyuNpJrJUOaLlBuy7qx6Es3VKoqOIilKWi5ZKXztvb5HlHlP/cf/AL5P+FFL5UP/AD0tf++//r0VXILTuLjPygEk8ADr+Fev/ss3kWjfFO21TUPEdp4b0O1gYak126Kt3E2QbcRn74Y8ng4xnrivHsndSJ8jccFeAfyrGlV9hUU9zvzLBrMcJPDN2Ula9r29Ez748WfHX4D6bp76fJrGseI7B0MY06znumtxGf4ANyjaBwASQBx2rx/VP2l/hnoKhfB/wX0jcDlLvWhGWVh0IQK5P/fYIr5oB5XHy/xcetCsSDzjPWu6pmdWWtkvlr+J8hhOCMBho8s5zmuqcml90bHsniz9rP4jeKLdrSDVYfDunkbRa6JALdVHpv5YD6EGvH55pLy5lubiR57mZi8k8rFndjjLMSSSTjqSTzUQJzQBuU8nj/61efUrzq6zlc+vwmW4LL4P6tSUb+Wv37/iM8yT/no/50Vb+zp6UVrY82yP/9kA" />
									</td>
									<td style="width:116pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="2">
										<p class="s1" style="padding-top: 5pt;padding-left: 18pt;text-indent: 0pt;text-align: left;">PT.Astra Daihatsu Motor</p>
									</td>
									<td style="width:238pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="3" rowspan="2">
										<p class="s2" style="padding-top: 6pt;padding-left: 54pt;text-indent: 0pt;text-align: left;">KANBAN DELIVERY</p>
									</td>
									<td style="width:154pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s1" style="padding-top: 5pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">ED Vendor No.</p>
									</td>
								</tr>
								<tr style="height:10pt">
									<td style="width:116pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="2">
										<p class="s1" style="padding-top: 1pt;text-indent: 0pt;text-align: center;">PL5</p>
									</td>
									<td style="width:154pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2">
										<p class="s2" style="padding-top: 2pt;text-indent: 0pt;text-align: center;">'.$data_kanban->ed_supplier_code.'</p>
									</td>
								</tr>
								<tr style="height:10pt">
									<td style="width:160pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="3">
										<p class="s1" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">No Part :</p>
									</td>
									<td style="width:74pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s1" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">Job No.</p>
									</td>
									<td style="width:164pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="2">
										<p class="s1" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">Plant Part No.</p>
									</td>
								</tr>
								<tr style="height:14pt">
									<td style="width:160pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="3" rowspan="2">
										<p class="s2" style="padding-top: 11pt;padding-left: 20pt;text-indent: 0pt;text-align: left;">'.$data_kanban->part_parent.'</p>
									</td>
									<td style="width:74pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s3" style="padding-top: 1pt;text-indent: 0pt;text-align: center;">-</p>
									</td>
									<td style="width:164pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="2">
										<p class="s3" style="padding-top: 1pt;padding-left: 43pt;text-indent: 0pt;text-align: left;">'.$data_kanban->part_no.'</p>
									</td>
									<td style="width:154pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2">
										<p style="text-indent: 0pt;text-align: left;">
											<br />
										</p>
										<p class="s4" style="padding-left: 10pt;text-indent: 0pt;text-align: left;">'.$data_kanban->ed_supplier_name.'</p>
									</td>
								</tr>
								<tr style="height:25pt">
									<td style="width:238pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="3">
										<p class="s3" style="padding-top: 6pt;padding-left: 43pt;text-indent: 0pt;text-align: left;">'.$data_kanban->part_name.'</p>
									</td>
								</tr>
								<tr style="height:10pt">
									<td style="width:44pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s1" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">PO NO.</p>
									</td>
									<td style="width:116pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" rowspan="2" colspan="2">
										<p class="s2" style="padding-top: 10pt;padding-left: 18pt;text-indent: 0pt;text-align: left;">'.$data_kanban->po_ed.'</p>
									</td>
									<td style="width:74pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s1" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">PO STO.</p>
									</td>
									<td style="width:118pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s1" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">Car Type</p>
									</td>
									<td style="width:46pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s1" style="padding-top: 1pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">Cycle Issue</p>
									</td>
									<td style="width:154pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s1" style="padding-top: 1pt;padding-left: 39pt;text-indent: 0pt;text-align: left;">RECEIVER ED VENDOR</p>
									</td>
								</tr>
								<tr style="height:24pt">
									<td style="width:44pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p style="text-indent: 0pt;text-align: left;">
											<br />
										</p>
									</td>
									<td style="width:74pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s5" style="padding-top: 7pt;padding-left: 13pt;text-indent: 0pt;text-align: left;">'.$data_kanban->po_sto.'</p>
									</td>
									<td style="width:118pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s5" style="padding-top: 7pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">'.$data_kanban->model.'</p>
									</td>
									<td style="width:46pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s5" style="padding-top: 7pt;padding-left: 8pt;text-indent: 0pt;text-align: left;">1:01:05</p>
									</td>
									<td style="width:154pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt; vertical-align: middle;" rowspan="3">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td style="padding-right:15px; vertical-align: bottom;">
													<span class="s6" style="padding-top: 7pt;padding-left: 40pt;text-indent: 0pt;text-align: left;">'.$customer.' <span>
												</td>
												<td>
													<div style="border:1px solid; padding:5px;">
														<div id="qrcode-'.$data_kanban->id.'-'.$i.'"></div>
													</div>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr style="height:9pt">
									<td style="width:65pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt;padding: 3px 3px 3px 3px;" colspan="2">
										<p class="s1" style="padding-left: 1pt;text-indent: 0pt;line-height: 7pt;text-align: left;">ORDER ISSUE</p>
									</td>
									<td style="width:95pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt;padding: 3px 3px 3px 3px;">
										<p class="s1" style="padding-left: 1pt;text-indent: 0pt;line-height: 7pt;text-align: left;">PLANT PROCESS</p>
									</td>
									<td style="width:74pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt;padding: 3px 3px 3px 3px;">
										<p class="s1" style="padding-left: 1pt;text-indent: 0pt;line-height: 7pt;text-align: left;">PLANT DELIVERY</p>
									</td>
									<td style="width:118pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt;padding: 3px 3px 3px 3px;">
										<p class="s1" style="padding-left: 1pt;text-indent: 0pt;line-height: 7pt;text-align: left;">TAM DELIVERY</p>
									</td>
									<td style="width:46pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt;padding: 3px 3px 3px 3px;">
										<p class="s1" style="padding-left: 1pt;text-indent: 0pt;line-height: 7pt;text-align: left;">QTY</p>
									</td>
								</tr>
								<tr style="height:19pt">
									<td style="width:65pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt;" colspan="2">
										<p class="s3" style="padding-top: 2pt;padding-left: 6pt;text-indent: 0pt;text-align: center;">-</p>
									</td>
									<td style="width:95pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s3" style="padding-top: 2pt;padding-left: 21pt;text-indent: 0pt;text-align: left;">'.date("d.m.Y",strtotime(getPreviousBusinessDay($data_kanban->finish_delivery))).'</p>
									</td>
									<td style="width:74pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s3" style="padding-top: 2pt;padding-left: 10pt;text-indent: 0pt;text-align: left;">'.date("d.m.Y",strtotime($data_kanban->finish_delivery)).'</p>
									</td>
									<td style="width:118pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s3" style="padding-top: 2pt;padding-left: 32pt;text-indent: 0pt;text-align: left;">'.date("d.m.Y",strtotime($data_kanban->mdp_date)).'</p>
									</td>
									<td style="width:46pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s3" style="padding-top: 2pt;padding-left: 13pt;text-indent: 0pt;text-align: left;">1 <span class="s7">/'.sprintf("%04d",$i).'</span>
										</p>
									</td>
								</tr>
							</table>
							<table style="border-collapse:collapse;margin-left:5.991pt; margin-top: 5px; min-width: 98%;" cellspacing="0">
								<tr style="height:13pt">
									<td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="2">
										<p class="s5" style="padding-top: 2pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">No Job</p>
									</td>
									<td style="width:128pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s5" style="padding-top: 2pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">Part Number</p>
									</td>
									<td style="width:45pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s5" style="padding-top: 2pt;padding-left: 1pt;text-indent: 0pt;text-align: left;">Total PO</p>
									</td>
									<td style="width:304pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt;text-align: center;vertical-align: middle;" colspan="2" rowspan="2">
										<img id="barcode-'.$data_kanban->id.'-'.$i.'" jsbarcode-value="#'.$data_kanban->part_no.' 1 '.$data_kanban->po_ed.' '.$data_kanban->po_sto.' '.date('Ymd',strtotime($data_kanban->finish_delivery)).' '.sprintf('%04d',$i).'#" jsbarcode-width="1" jsbarcode-height="23" jsbarcode-format="code128" jsbarcode-displayValue="false" width="80%">
									</td>
								</tr>
								<tr style="height:30pt">
									<td style="width:75pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt" colspan="2">
										<p class="s8" style="padding-top: 8pt;text-indent: 0pt;text-align: center;">-</p>
									</td>
									<td style="width:128pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s8" style="padding-top: 8pt;padding-left: 10pt;text-indent: 0pt;text-align: left;">'.$data_kanban->part_parent.'</p>
									</td>
									<td style="width:45pt;border-top-style:solid;border-top-width:1pt;border-left-style:solid;border-left-width:1pt;border-bottom-style:solid;border-bottom-width:1pt;border-right-style:solid;border-right-width:1pt">
										<p class="s8" style="padding-top: 8pt;padding-left: 15pt;text-indent: 0pt;text-align: left;">'.$data_kanban->qty_plan.'</p>
									</td>
								</tr>
							</table>
							<hr class="dashed-line">';
						}
					}
				}
			}
		}
	}
	?>
</body>
</html>
<script src="<?= base_url("assets/vendor/qrcodejs/qrcode.min.js"); ?>"></script>
<script src="<?= base_url("assets/vendor/barcodejs/JsBarcode.all.min.js"); ?>"></script>
<script>
	<?php
	if(!empty($data_kanban_printJs)){
		foreach ($data_kanban_printJs as $data_kanban_printJs) {
			$data_kanban = $this->model->gd("master","*","part_no = '$data_kanban_printJs->part_no' AND po_ed = '$data_kanban_printJs->po_ed' AND po_sto = '$data_kanban_printJs->po_sto' AND finish_delivery = '$data_kanban_printJs->pdd'","row");
			if($data_kanban->ed_supplier_name == "TAIKISHA MANUFACTURING INDONESIA"){
				for ($i=1; $i <= $data_kanban_printJs->qty; $i++) {
					$seq_kanban = '"seq":"'.$i.'"';
					if(substr_count($seq_json,$seq_kanban) <= 0){
						?>
						var qrcode = new QRCode("qrcode-<?= $data_kanban->id."-".$i; ?>", {
							text: "#<?= $data_kanban->part_no." 1 ".$data_kanban->po_ed." ".$data_kanban->po_sto." ".date("Ymd",strtotime($data_kanban->finish_delivery))." ".sprintf("%04d",$i); ?>#",
							width: 58,
							height: 58,
							colorDark : "#000000",
							colorLight : "#ffffff",
							correctLevel : QRCode.CorrectLevel.L
						});
		
						JsBarcode("#barcode-<?= $data_kanban->id."-".$i; ?>").init();
						<?php
					}
				}
			}
		}
	}
	?>
</script>
