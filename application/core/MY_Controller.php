<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		set_time_limit(60);
		$this->urlspd = 'https://adm-inhspd.daihatsu.astra.co.id/index.php';
		$this->p1 = $this->uri->segment(1);
	}

	function swal($title,$msg,$icon,$redirect = null)
	{
		$swal = '
		<script src="'.base_url("assets/vendor/sweetalert2/dist/sweetalert2.all.min.js").'"></script>
		<script>Swal.fire(`'.$title.'`,`'.$msg.'`,`'.$icon.'`)</script>';
		$this->session->set_flashdata(array("swal" => $swal));
		if(!empty($redirect)){
			redirect($redirect);
		}
	}

	function notif($msg,$type,$redirect = null)
	{
		if($type == "success"){
			$icon_btn = "fas fa-check";
			$color_notif = "btn-success";
		}else if($type == "warning"){
			$icon_btn = "fas fa-triangle-exclamation";
			$color_notif = "btn-warning";
		}else if($type == "danger"){
			$icon_btn = "fas fa-times";
			$color_notif = "btn-danger";
		}
		$notif = '
		<h6 class="btn '.$color_notif.' btn-icon-split rounded mb-3">
			<span class="icon text-white-50">
				<i class="'.$icon_btn.'"></i>
			</span>
			<span class="text" style="font-size:10pt;">'.$msg.'</span>
		</h6>';
		$this->session->set_flashdata(array("notif" => $notif));
		if(!empty($redirect)){
			redirect($redirect);
		}
	}

	function fb($res)
	{
		echo json_encode($res);
		die();
	}
}
