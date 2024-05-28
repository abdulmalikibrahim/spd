<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		set_time_limit(60);
		$this->urlspd = 'https://adm-inhspd.daihatsu.astra.co.id/index.php';
	}
}
