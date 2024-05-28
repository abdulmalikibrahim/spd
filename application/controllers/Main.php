<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Main extends MY_Controller {

	public function index()
	{
		$data["content"] = "dashboard";
		$data["title"] = "Dashboard";
		$this->load->view("layout/index",$data);
	}
}
