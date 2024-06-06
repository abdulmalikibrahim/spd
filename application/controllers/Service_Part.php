<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Service_Part extends MY_Controller {

	public function all()
	{
		if(empty($this->input->post("start-date"))){
			$start_date = date("Y-m-01");
		}else{
			$start_date = $this->input->post("start-date");
		}
		if(empty($this->input->post("end-date"))){
			$end_date = date("Y-m-t");
		}else{
			$end_date = $this->input->post("end-date");
		}
		$data["js_add"] = "data_spd";
		$data["content"] = "data_spd";
		$data["title"] = "SPD ALL";
		$data["start_date"] = $start_date;
		$data["end_date"] = $end_date;
		$data["data"] = $this->model->gd("master","*","finish_delivery BETWEEN '$start_date' AND '$end_date' ORDER BY finish_delivery ASC","result");
		$this->load->view("layout/index",$data);
	}

	public function remain()
	{
		if(empty($this->input->post("start-date"))){
			$start_date = date("Y-m-01");
		}else{
			$start_date = $this->input->post("start-date");
		}
		if(empty($this->input->post("end-date"))){
			$end_date = date("Y-m-t");
		}else{
			$end_date = $this->input->post("end-date");
		}
		$data["js_add"] = "data_spd";
		$data["content"] = "data_spd";
		$data["title"] = "SPD REMAIN";
		$data["start_date"] = $start_date;
		$data["end_date"] = $end_date;
		$data["data"] = $this->model->gd("master a","*,(SELECT COUNT(id) FROM scan_process b WHERE b.part_no = a.part_no AND b.po_sto = a.po_sto AND b.pdd = a.finish_delivery) AS total_process, (a.qty_plan - (SELECT COUNT(id) FROM scan_process c WHERE c.part_no = a.part_no AND c.po_sto = a.po_sto AND c.pdd = a.finish_delivery)) AS total_remain","remain_out_plant > 0 AND finish_delivery BETWEEN '$start_date' AND '$end_date' HAVING total_remain > 0 AND id != '' ORDER BY finish_delivery ASC","result");
		$this->load->view("layout/index",$data);
	}

	public function close()
	{
		if(empty($this->input->post("start-date"))){
			$start_date = date("Y-m-01");
		}else{
			$start_date = $this->input->post("start-date");
		}
		if(empty($this->input->post("end-date"))){
			$end_date = date("Y-m-d");
		}else{
			$end_date = $this->input->post("end-date");
		}
		$data["js_add"] = "data_spd";
		$data["content"] = "data_spd";
		$data["title"] = "SPD CLOSE";
		$data["start_date"] = $start_date;
		$data["end_date"] = $end_date;
		$data["data"] = $this->model->gd("master","*","remain_out_plant <= 0 AND finish_delivery BETWEEN '$start_date' AND '$end_date' ORDER BY finish_delivery ASC","result");
		$this->load->view("layout/index",$data);
	}

	function already_process()
	{
		$id = d_nzm($this->input->get("id"));
		$back = $this->input->get("back");
		if(substr_count($id,"error") > 0){
			$this->swal("Error","Decryption Error","error",$back);
		}
		$data_kanban = $this->model->gd("master","*","id = '$id'","row");
		if(!empty($data_kanban->qty_plan)){
			for ($i=1; $i <= $data_kanban->qty_plan; $i++) {
				$scan = "#".$data_kanban->part_no." 1 ".$data_kanban->po_ed." ".$data_kanban->po_sto." ".date("Ymd",strtotime($data_kanban->finish_delivery))." ".sprintf("%04d",$i)."#";
				
				$part_no = $data_kanban->part_no;
				$qty = "1";
				$po_ed = $data_kanban->po_ed;
				$po_sto = $data_kanban->po_sto;
				$pdd = $data_kanban->finish_delivery;
				$seq = $i;
				$data = [
					"tanggal" => date("Y-m-d H:i:s"),
					"barcode" => $scan,
					"part_no" => $part_no,
					"qty" => $qty,
					"po_ed" => $po_ed,
					"po_sto" => $po_sto,
					"pdd" => $pdd,
					"seq" => $seq,
				];
				//SUBMIT PROCESS
				$submit = $this->model->insert("scan_process",$data);
			}
			if($submit){
				$this->swal("Sukses","Data berhasil di masukkan ke already process","success",$back);
			}else{
				$this->swal("Error","Update data gagal","error",$back);
			}
		}
	}

	function create_wos()
	{
		$plan = $this->input->post("plan[]");
		if(!empty($plan)){
			foreach ($plan as $key => $value) {
				$parsing_data = explode("#",$value);
				$part_no = $parsing_data[0];
				$po_sto = $parsing_data[1];
				$pdd = $parsing_data[2];
				//CARI QTY
				$check_qty = $this->model->gd("master","qty_plan,part_name","part_no = '$part_no' AND po_sto = '$po_sto' AND finish_delivery = '$pdd'","row");
				$check_scan_out = $this->model->gd("scan_process","seq","part_no = '$part_no' AND po_sto = '$po_sto' AND pdd = '$pdd'","result");
				if(empty($check_qty->qty_plan)){
					$this->swal("Error","Data ".$check_qty->part_name." (".$part_no.") PO = ".$po_sto." qty plan kosong, mohon periksa data anda kembali","error","spd_remain");
				}

				$qty = 0;
				$check_scan_out_json = json_encode($check_scan_out);
				for ($i=1; $i <= $check_qty->qty_plan ; $i++) {
					//CARI SEQ
					$seq = '"seq":"'.$i.'"';
					if(substr_count($check_scan_out_json,$seq) <= 0){
						$qty++;
					}
				}
				$po_ed = $parsing_data[4];
				//MASUKKAN DATA KE PLANNING
				$data_planning[] = [
					"tanggal" => date("Y-m-d"),
					"part_no" => $part_no,
					"po_sto" => $po_sto,
					"po_ed" => $po_ed,
					"pdd" => $pdd,
					"qty" => $qty,
				];
			}
			$insert = $this->model->insert_batch("planning_spd",$data_planning);
			$this->swal("Sukses","Setup WOS berhasil, silahkan klik Setup WOS Now untuk print Kanban & WOS SPD","success","spd_remain");
		}else{
			$this->swal("Warning","Tidak ada planning yang anda centang","warning","spd_remain");
		}
	}

	function print_wos()
	{
		$data["content"] = "print_wos";
		$data["title"] = "PRINT WOS";
		$tanggal = $this->input->get("tanggal");
		$data["planning"] = $this->model->gd("planning_spd","*","tanggal = '$tanggal'","result");
		$this->load->view("content/page/print_wos",$data);
	}

	function print_kanban_asi()
	{
		$data["content"] = "print_kanban_asi";
		$data["title"] = "PRINT KANBAN ASI";
		$tanggal = $this->input->get("tanggal");
		$data["data_kanban_print"] = $this->model->gd("planning_spd","*","tanggal = '$tanggal'","result");
		$data["data_kanban_printJs"] = $this->model->gd("planning_spd","*","tanggal = '$tanggal'","result");
		$this->load->view("content/page/print_kanban_asi",$data);
	}

	function print_kanban_tmi()
	{
		$data["content"] = "print_kanban_tmi";
		$data["title"] = "PRINT KANBAN TMI";
		$tanggal = $this->input->get("tanggal");
		$data["data_kanban_print"] = $this->model->gd("planning_spd","*","tanggal = '$tanggal'","result");
		$data["data_kanban_printJs"] = $this->model->gd("planning_spd","*","tanggal = '$tanggal'","result");
		$this->load->view("content/page/print_kanban_tmi",$data);
	}

	function closing_planning()
	{
		//UPDATE DATA WOS ALREADY PRINT
		$list_kanban = $this->model->gd("planning_spd","*","already_print = '0'","result");
		
		if(!empty($list_kanban)){
			foreach ($list_kanban as $data_kanban) {
				//CARI SEQ SCAN OUT
				$seq = $this->model->gd("scan_process","seq","part_no = '$data_kanban->part_no' AND po_ed = '$data_kanban->po_ed' AND po_sto = '$data_kanban->po_sto' AND pdd = '$data_kanban->pdd'","result");
				$seq_json = json_encode($seq);

				//QTY PLAN
				$qty_plan = $this->model->gd("master","qty_plan,part_name","part_no = '$data_kanban->part_no' AND po_ed = '$data_kanban->po_ed' AND po_sto = '$data_kanban->po_sto' AND finish_delivery = '$data_kanban->pdd'","row");

				if(empty($qty_plan->qty_plan)){
					$this->swal("Error","Qty Plan untuk ".$qty_plan->part_name." (".$data_kanban->part_no.") PO = ".$data_kanban->po_sto." tidak ada, mohon periksa kembali data anda","spd_remain");
				}

				$qty_plan = $qty_plan->qty_plan;

				$data = [];
				//DETAIL KANBAN
				for ($i=1; $i <= $qty_plan; $i++) {
					//SEQ CURRENT
					$seq = '"seq":"'.$i.'"';
					if(substr_count($seq_json,$seq) <= 0){
						//CHECK FOR NOT DOUBLE
						$barcode = '#'.$data_kanban->part_no.' 1 '.$data_kanban->po_ed.' '.$data_kanban->po_sto.' '.date('Ymd',strtotime($data_kanban->pdd)).' '.sprintf('%04d',$i).'#';
						$validasi = $this->model->gd("scan_process","id","barcode = '$barcode'","row");
						if(empty($validasi->id)){
							//INSERT TO SCAN OUT
							$data[] = [
								"tanggal" => date("Y-m-d H:i:s"),
								"barcode" => $barcode,
								"part_no" => $data_kanban->part_no,
								"qty" => "1",
								"po_ed" => $data_kanban->po_ed,
								"po_sto" => $data_kanban->po_sto,
								"pdd" => $data_kanban->pdd,
								"seq" => $i,
							];
						}
					}
				}
				if(!empty($data)){
					$this->model->insert_batch("scan_process",$data);
					//UPDATE SUDAH PRINT
					$data_update = ["already_print" => "1"];
					$this->model->update("planning_spd","id = '$data_kanban->id'",$data_update);
				}
			}
		}
		echo '
		<script>
			// Tutup tab setelah cetak atau pembatalan cetak
			window.close();
		</script>';
	}
}
