<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Planning extends MY_Controller {

	public function planning_spd()
	{
		if(!empty($this->input->post("planning-date"))){
			$planning_date = $this->input->post("planning-date");
		}else{
			$planning_date = date("Y-m-d");
		}
		$data["planning_date"] = $planning_date;
		$data["data"] = $this->model->gd("planning_spd","*","tanggal = '".$planning_date."'","result");
		$data["js_add"] = "planning_spd";
		$data["content"] = "planning_spd";
		$data["title"] = "PLANNING SPD";
		$this->load->view("layout/index",$data);
	}
}
