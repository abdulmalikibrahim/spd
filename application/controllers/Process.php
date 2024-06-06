<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Process extends MY_Controller {

	public function scan_process()
	{
		$data["data"] = $this->model->gd("scan_process","id,barcode","scan_out = 'X'","result");
		$data["js_add"] = "scan_process";
		$data["content"] = "scan_process";
		$data["title"] = "SCAN PROCESS";
		$this->load->view("layout/index",$data);
	}
	public function cancel_process()
	{
		$data["js_add"] = "cancel_process";
		$data["content"] = "cancel_process";
		$data["title"] = "CANCEL PROCESS";
		$this->load->view("layout/index",$data);
	}
	public function print_kanban()
	{
		$id = d_nzm($this->input->get("id"));
		if(substr_count($id,"error") > 0){
			echo '<center><h3>ID NOT VALID</h3></center>';
			die();
		}
		$data["data_kanban"] = $this->model->gd("master","*","id = '$id'","row");
		$this->load->view("content/page/print_kanban",$data);
	}
	function scan_processing()
	{
		$this->form_validation->set_rules("scan","QR CODE","required|trim|xss_clean");
		if($this->form_validation->run() === FALSE){
			$this->swal("Warning",validation_errors(),"warning","scan_process");
		}
		$scan = $this->input->post("scan");
		//CHECK BARCODE
		$validasi = $this->model->gd("scan_process","id","barcode = '$scan'","row");
		if(!empty($validasi->id)){
			$this->notif($scan." double scan","warning","scan_process");
		}
		$parsing_barcode = explode(" ",str_replace("#","",$scan));
		$part_no = $parsing_barcode[0];
		$qty = $parsing_barcode[1];
		$po_ed = $parsing_barcode[2];
		$po_sto = $parsing_barcode[3];
		$pdd = substr($parsing_barcode[4],0,4)."-".substr($parsing_barcode[4],4,2)."-".substr($parsing_barcode[4],6,2);
		$seq = intval($parsing_barcode[5]);
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
		if($submit){
			$this->notif($scan." berhasil","success","scan_process");
		}else{
			$this->swal("Error","Process scan gagal","error","scan_process");
		}
	}
	function update_scan_out()
	{
		$this->form_validation->set_rules("barcode","QR CODE","required|trim|xss_clean");
		if($this->form_validation->run() === FALSE){
			$fb = ["status" => 400, "title" => "Warning", "res" => validation_errors(), "icon" => "warning"];
			$this->fb($fb);
		}
		$dataBarcode = json_decode($this->input->post("barcode"),true);
		foreach ($dataBarcode as $key => $barcode) {
			$data = [
				"scan_out" => "O",
				"scan_out_time" => date("Y-m-d H:i:s"),
			];
			//SUBMIT PROCESS
			$submit = $this->model->update("scan_process","barcode LIKE '%$barcode%'",$data);
			if($submit){
				$fb = ["status" => 200];
			}else{
				$fb = ["status" => 500, "title" => "Error", "res" => "Data gagal update", "icon" => "warning"];
			}
		}
		$this->fb($fb);
	}
	function cancel_processing()
	{
		if(!empty($this->input->get("barcode"))){
			$scan = d_nzm($this->input->get("barcode"));
			$redirect = "scan_process";
		}else{
			$redirect = "cancel_process";
			$this->form_validation->set_rules("scan","QR CODE","required|trim|xss_clean");
			if($this->form_validation->run() === FALSE){
				$this->swal("Warning",validation_errors(),"warning",$redirect);
			}
			$scan = $this->input->post("scan");
		}
		//CHECK BARCODE
		$validasi = $this->model->gd("scan_process","id","barcode = '$scan'","row");
		if(empty($validasi->id)){
			$this->notif($scan." tidak ada data","danger",$redirect);
		}
		//CANCEL PROCESS
		$cancel = $this->model->delete("scan_process","barcode = '$scan'");
		if($cancel){
			$this->notif($scan." canceled","success",$redirect);
		}else{
			$this->swal("Error","Process cancel scan gagal","error",$redirect);
		}
	}
	public function scan_out()
	{
		$data["data"] = $this->model->gd("scan_process","*,COUNT(id) as qty_total","scan_out = 'X' GROUP BY part_no,po_ed,po_sto,pdd","result");
		$data["js_add"] = "scan_out";
		$data["content"] = "scan_out";
		$data["title"] = "SCAN OUT";
		$this->load->view("layout/index",$data);
	}
	public function failed_scan_out()
	{
		$data["data"] = $this->model->gd("scan_process a","*,COUNT(id) as qty_total","a.scan_out = 'O' GROUP BY part_no,po_ed,po_sto,pdd","result");
		$data["js_add"] = "failed_scan_out";
		$data["content"] = "failed_scan_out";
		$data["title"] = "FAILED SCAN OUT";
		$this->load->view("layout/index",$data);
	}
	function cancel_scan_out()
	{
		if(!empty($this->input->get("barcode"))){
			$scan = d_nzm($this->input->get("barcode"));
			$redirect = "scan_process";
		}else{
			$redirect = "cancel_process";
			$this->form_validation->set_rules("scan","QR CODE","required|trim|xss_clean");
			if($this->form_validation->run() === FALSE){
				$this->swal("Warning",validation_errors(),"warning",$redirect);
			}
			$scan = $this->input->post("scan");
		}
		//CHECK BARCODE
		$validasi = $this->model->gd("scan_process","id","barcode LIKE '%$scan%'","row");
		if(empty($validasi->id)){
			$this->notif($scan." tidak ada data","danger",$redirect);
		}
		//CANCEL PROCESS
		$data_update = [
			"scan_out" => "X",
			"scan_out_time" => NULL,
		];
		$cancel = $this->model->update("scan_process","barcode LIKE '%$scan%'",$data_update);
		if($cancel){
			$fb = ["status" => 200];
		}else{
			$fb = ["status" => 500];
		}
		$this->fb($fb);
	}
}
